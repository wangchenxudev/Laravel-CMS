<?php

use App\Enums\Article\ArticleStatus;
use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authors can soft delete their editable articles', function (ArticleStatus $status) {
    $author = User::factory()->create();
    $article = Article::factory()->create([
        'author_id' => $author->id,
        'status' => $status,
    ]);

    $this->actingAs($author)
        ->delete(route('articles.destroy', $article))
        ->assertRedirect(route('articles.index', absolute: false));

    expect(Article::query()->find($article->id))->toBeNull()
        ->and(Article::withTrashed()->find($article->id)->trashed())->toBeTrue();
})->with([
    'draft' => ArticleStatus::Draft,
    'withdrawn' => ArticleStatus::Withdrawn,
    'rejected' => ArticleStatus::Rejected,
]);

test('authors can not delete articles that are not editable', function (ArticleStatus $status) {
    $author = User::factory()->create();
    $article = Article::factory()->create([
        'author_id' => $author->id,
        'status' => $status,
    ]);

    $this->actingAs($author)
        ->delete(route('articles.destroy', $article))
        ->assertForbidden();

    expect(Article::query()->find($article->id))->not->toBeNull();
})->with([
    'pending review' => ArticleStatus::PendingReview,
    'published' => ArticleStatus::Published,
    'taken down' => ArticleStatus::TakenDown,
]);

test('users can not delete articles they do not own', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $article = Article::factory()->create([
        'author_id' => $owner->id,
        'status' => ArticleStatus::Draft,
    ]);

    $this->actingAs($otherUser)
        ->delete(route('articles.destroy', $article))
        ->assertForbidden();

    expect(Article::query()->find($article->id))->not->toBeNull();
});

test('guests can not delete articles', function () {
    $article = Article::factory()->create([
        'status' => ArticleStatus::Draft,
    ]);

    $this->delete(route('articles.destroy', $article))
        ->assertRedirect(route('login', absolute: false));
});

test('soft deleted articles are hidden from the author list', function () {
    $author = User::factory()->create();
    $article = Article::factory()->create([
        'author_id' => $author->id,
        'title' => 'Soft Deleted Draft',
        'status' => ArticleStatus::Draft,
    ]);

    $article->delete();

    $this->actingAs($author)
        ->get(route('articles.index'))
        ->assertOk()
        ->assertDontSee('Soft Deleted Draft');
});

test('soft deleted published articles are hidden from public pages', function () {
    $article = Article::factory()->create([
        'title' => 'Soft Deleted Published',
        'status' => ArticleStatus::Published,
        'approved_at' => now(),
    ]);

    $article->delete();

    $this->get(route('published.articles.index'))
        ->assertOk()
        ->assertDontSee('Soft Deleted Published');

    $this->get(route('published.articles.show', [
        'article' => $article->id,
        'slug' => $article->slug,
    ]))->assertNotFound();
});
