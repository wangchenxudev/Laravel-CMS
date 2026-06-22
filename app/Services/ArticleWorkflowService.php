<?php

namespace App\Services;

use App\Enums\Article\ArticleReviewActionType;
use App\Enums\Article\ArticleStatus as ArticleStatusEnum;
use App\Models\Article;
use App\Models\ArticleReviewAction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ArticleWorkflowService
{
    public function submit(Article $article): Article
    {
        if (! $article->status->isEditable()) {
            throw ValidationException::withMessages([
                'article' => 'Only draft, withdrawn, or rejected articles can be submitted.',
            ]);
        }

        $article->update([
            'status' => ArticleStatusEnum::PendingReview,
            'approved_by' => null,
            'approved_at' => null,
            'rejected_by' => null,
            'rejected_at' => null,
            'reject_reason' => null,
            'withdrawn_at' => null,
            'taken_down_at' => null,
        ]);

        return $article->refresh();
    }

    public function withdraw(Article $article): Article
    {
        if ($article->status !== ArticleStatusEnum::PendingReview) {
            throw ValidationException::withMessages([
                'article' => 'Only pending articles can be withdrawn.',
            ]);
        }

        $article->update([
            'status' => ArticleStatusEnum::Withdrawn,
            'withdrawn_at' => now(),
        ]);

        return $article->refresh();
    }

    public function approve(Article $article, User $admin): Article
    {
        return $this->review(
            article: $article,
            admin: $admin,
            actionType: ArticleReviewActionType::Approve,
            toStatus: ArticleStatusEnum::Published,
        );
    }

    public function reject(Article $article, User $admin, string $reason): Article
    {
        return $this->review(
            article: $article,
            admin: $admin,
            actionType: ArticleReviewActionType::Reject,
            toStatus: ArticleStatusEnum::Rejected,
            reason: $reason,
        );
    }

    public function takeDown(Article $article, User $admin, ?string $reason = null): Article
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
    ): Article {
        return DB::transaction(function () use ($article, $admin, $actionType, $toStatus, $reason, $allowedFrom): Article {
            $article = Article::query()
                ->whereKey($article->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (! in_array($article->status, $allowedFrom, true)) {
                throw ValidationException::withMessages([
                    'article' => 'This article is not in a reviewable status.',
                ]);
            }

            ArticleReviewAction::query()->create([
                'article_id' => $article->id,
                'admin_id' => $admin->id,
                'action' => $actionType,
                'from_status' => $article->status,
                'to_status' => $toStatus,
                'reason' => $reason,
            ]);

            $article->update($this->statusUpdatesFor($actionType, $toStatus, $admin, $reason));

            return $article->refresh();
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
}
