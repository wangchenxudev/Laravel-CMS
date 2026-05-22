<?php

namespace App\Policies;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Article $article): bool
    {
        return $article->author_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Article $article): bool
    {
        return $this->ownsArticle($user, $article)
            && in_array($article->currentStatus?->status, [
                ArticleStatus::Draft,
                ArticleStatus::Withdrawn,
                ArticleStatus::Rejected,
            ], true);
    }

    public function submit(User $user, Article $article): bool
    {
        return $this->update($user, $article);
    }

    public function withdraw(User $user, Article $article): bool
    {
        return $this->ownsArticle($user, $article)
            && $article->currentStatus?->status === ArticleStatus::PendingReview;
    }

    private function ownsArticle(User $user, Article $article): bool
    {
        return $article->author_id === $user->id;
    }
}
