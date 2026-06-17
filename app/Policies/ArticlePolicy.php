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
        return $user->isAdmin() || $this->ownsArticle($user, $article);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Article $article): bool
    {
        return $this->ownsArticle($user, $article)
            && in_array($article->status, [
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
            && $article->status === ArticleStatus::PendingReview;
    }

    public function approve(User $user, Article $article): bool
    {
        return $user->isAdmin();
    }

    public function reject(User $user, Article $article): bool
    {
        return $user->isAdmin();
    }

    public function takeDown(User $user, Article $article): bool
    {
        return $user->isAdmin();
    }

    private function ownsArticle(User $user, Article $article): bool
    {
        return $article->author_id === $user->id;
    }
}
