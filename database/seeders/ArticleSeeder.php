<?php

namespace Database\Seeders;

use App\Enums\ArticleStatus;
use App\Enums\UserRole;
use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::query()
            ->where('role', UserRole::Admin)
            ->firstOrFail();

        $authors = User::query()
            ->where('role', UserRole::User)
            ->orderBy('id')
            ->get();

        for ($number = 1; $number <= 20; $number++) {
            $author = $authors[($number - 1) % $authors->count()];
            $status = $this->statusFor($number);
            $approvedAt = $status === ArticleStatus::Published
                ? now()->subDays(20 - $number)
                : null;

            $article = Article::factory()->make([
                'author_id' => $author->id,
                'title' => sprintf('Demo Article %02d', $number),
                'status' => $status,
                'approved_by' => $approvedAt ? $admin->id : null,
                'approved_at' => $approvedAt,
                'rejected_by' => $status === ArticleStatus::Rejected ? $admin->id : null,
                'rejected_at' => $status === ArticleStatus::Rejected ? now()->subDays($number) : null,
                'reject_reason' => $status === ArticleStatus::Rejected ? 'Demo article needs revision.' : null,
                'withdrawn_at' => $status === ArticleStatus::Withdrawn ? now()->subDays($number) : null,
                'taken_down_at' => $status === ArticleStatus::TakenDown ? now()->subDays($number) : null,
            ]);

            Article::query()->updateOrCreate([
                'title' => $article->title,
            ], $article->getAttributes());
        }
    }

    private function statusFor(int $number): ArticleStatus
    {
        return match (true) {
            $number <= 10 => ArticleStatus::Published,
            $number <= 14 => ArticleStatus::Draft,
            $number <= 17 => ArticleStatus::PendingReview,
            $number <= 19 => ArticleStatus::Rejected,
            default => ArticleStatus::TakenDown,
        };
    }
}
