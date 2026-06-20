<?php

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('database seeder creates users and twenty demo articles', function () {
    $this->seed(DatabaseSeeder::class);

    $article = Article::query()
        ->where('title', 'Demo Article 01')
        ->firstOrFail();

    expect(User::query()->count())->toBe(3)
        ->and(Article::query()->count())->toBe(20)
        ->and(Article::query()->where('status', ArticleStatus::Published)->count())->toBe(10)
        ->and($article->slug)->toBe('demo-article-01')
        ->and($article->getAttributes())->not->toHaveKey('slug');
});
