<?php

namespace App\Services;

use App\Enums\ArticleReviewActionType;
use App\Enums\ArticleStatus as ArticleStatusEnum;
use App\Models\Article;
use App\Models\ArticleReviewAction;
use App\Models\ArticleStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ArticleWorkflowService
{
    public function createDraftStatus(Article $article): ArticleStatus
    {
        return ArticleStatus::query()->create([
            'article_id' => $article->id,
            'status' => ArticleStatusEnum::Draft,
        ]);
    }

    public function submit(Article $article): ArticleStatus
    {
        $status = $this->statusFor($article);

        if (! in_array($status->status, [
            ArticleStatusEnum::Draft,
            ArticleStatusEnum::Withdrawn,
            ArticleStatusEnum::Rejected,
        ], true)) {
            throw ValidationException::withMessages([
                'article' => 'Only draft, withdrawn, or rejected articles can be submitted.',
            ]);
        }

        $status->update([
            'status' => ArticleStatusEnum::PendingReview,
            'approved_by' => null,
            'approved_at' => null,
            'rejected_by' => null,
            'rejected_at' => null,
            'reject_reason' => null,
            'withdrawn_at' => null,
            'taken_down_at' => null,
        ]);

        return $status;
    }

    public function withdraw(Article $article): ArticleStatus
    {
        $status = $this->statusFor($article);

        if ($status->status !== ArticleStatusEnum::PendingReview) {
            throw ValidationException::withMessages([
                'article' => 'Only pending articles can be withdrawn.',
            ]);
        }

        $status->update([
            'status' => ArticleStatusEnum::Withdrawn,
            'withdrawn_at' => now(),
        ]);

        return $status;
    }

    public function approve(Article $article, User $admin): ArticleStatus
    {
        return $this->review(
            article: $article,
            admin: $admin,
            actionType: ArticleReviewActionType::Approve,
            toStatus: ArticleStatusEnum::Published,
        );
    }

    public function reject(Article $article, User $admin, string $reason): ArticleStatus
    {
        return $this->review(
            article: $article,
            admin: $admin,
            actionType: ArticleReviewActionType::Reject,
            toStatus: ArticleStatusEnum::Rejected,
            reason: $reason,
        );
    }

    public function takeDown(Article $article, User $admin, ?string $reason = null): ArticleStatus
    {
        return $this->review(
            article: $article,
            admin: $admin,
            actionType: ArticleReviewActionType::TakeDown,
            toStatus: ArticleStatusEnum::TakenDown,
            reason: $reason,
            allowedFrom: [ArticleStatusEnum::Published],
        );
    }

    /**
     * @param  array<int, ArticleStatusEnum>  $allowedFrom
     */
    private function review(
        Article $article,
        User $admin,
        ArticleReviewActionType $actionType,
        ArticleStatusEnum $toStatus,
        ?string $reason = null,
        array $allowedFrom = [ArticleStatusEnum::PendingReview],
    ): ArticleStatus {
        return DB::transaction(function () use ($article, $admin, $actionType, $toStatus, $reason, $allowedFrom): ArticleStatus {
            $status = ArticleStatus::query()
                ->where('article_id', $article->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (! in_array($status->status, $allowedFrom, true)) {
                throw ValidationException::withMessages([
                    'article' => 'This article is not in a reviewable status.',
                ]);
            }

            $action = ArticleReviewAction::query()->create([
                'article_id' => $article->id,
                'admin_id' => $admin->id,
                'action' => $actionType,
                'from_status' => $status->status,
                'to_status' => $toStatus,
                'reason' => $reason,
                'is_open' => true,
                'open_slot' => 'open',
            ]);

            $status->update($this->statusUpdatesFor($actionType, $toStatus, $admin, $reason));

            $action->update([
                'is_open' => false,
                'open_slot' => null,
            ]);

            return $status->refresh();
        });
    }

    /**
     * @return array<string, mixed>
     */
    private function statusUpdatesFor(
        ArticleReviewActionType $actionType,
        ArticleStatusEnum $toStatus,
        User $admin,
        ?string $reason,
    ): array {
        if ($actionType === ArticleReviewActionType::Approve) {
            return [
                'status' => $toStatus,
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'rejected_by' => null,
                'rejected_at' => null,
                'reject_reason' => null,
                'withdrawn_at' => null,
                'taken_down_at' => null,
            ];
        }

        if ($actionType === ArticleReviewActionType::Reject) {
            return [
                'status' => $toStatus,
                'rejected_by' => $admin->id,
                'rejected_at' => now(),
                'reject_reason' => $reason,
                'approved_by' => null,
                'approved_at' => null,
                'taken_down_at' => null,
            ];
        }

        return [
            'status' => $toStatus,
            'taken_down_at' => now(),
        ];
    }

    private function statusFor(Article $article): ArticleStatus
    {
        return $article->currentStatus()->firstOrFail();
    }
}
