<?php

namespace App\Policies;

use App\Enums\Article\ArticleStatus;
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
            && $article->status->isEditable();
    }

    public function submit(User $user, Article $article): bool
    {
        return $this->update($user, $article);
    }

    public function updateTags(User $user, Article $article): bool
    {
        return $user->isAdmin()
            || ($this->ownsArticle($user, $article) && $article->status !== ArticleStatus::TakenDown);
    }

    public function delete(User $user, Article $article): bool
    {
        return $this->ownsArticle($user, $article)
            && $article->status->isEditable();
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
