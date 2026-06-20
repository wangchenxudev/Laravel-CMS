<?php

use App\Enums\ArticleReviewActionType;
use App\Enums\ArticleStatus as ArticleStatusEnum;
use App\Enums\UserRole;
use App\Models\Article;
use App\Models\ArticleReviewAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function articlePayload(array $overrides = []): array
{
    return array_merge([
        'title' => 'Reviewable Article',
        'summary' => 'A short summary.',
        'content' => 'A complete article body.',
    ], $overrides);
}

test('authenticated users can create draft articles tied to their user id', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('articles.store'), articlePayload());

    $article = Article::query()->where('title', 'Reviewable Article')->firstOrFail();

    $response->assertRedirect(route('articles.show', $article, absolute: false));

    expect($article->author_id)->toBe($user->id)
        ->and($article->slug)->toBe('reviewable-article')
        ->and($article->status)->toBe(ArticleStatusEnum::Draft)
        ->and($article->getAttributes())->not->toHaveKey('slug');
});

test('guests can not create articles', function () {
    $response = $this->post(route('articles.store'), articlePayload());

    $response->assertRedirect(route('login', absolute: false));
});

test('users can only view and edit their own articles', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $article = Article::factory()->create([
        'author_id' => $owner->id,
    ]);

    $this->actingAs($owner)->get(route('articles.show', $article))->assertOk();
    $this->actingAs($otherUser)->get(route('articles.show', $article))->assertForbidden();
    $this->actingAs($otherUser)->get(route('articles.edit', $article))->assertForbidden();
    $this->actingAs($admin)->get(route('admin.articles.show', $article))->assertOk();
});

test('users can submit and withdraw pending articles', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create([
        'author_id' => $user->id,
    ]);

    $this->actingAs($user)->post(route('articles.submit', $article))->assertRedirect(route('articles.show', $article, absolute: false));

    expect($article->refresh()->status)->toBe(ArticleStatusEnum::PendingReview);

    $this->actingAs($user)->get(route('articles.edit', $article))->assertForbidden();

    $this->actingAs($user)->post(route('articles.withdraw', $article))->assertRedirect(route('articles.show', $article, absolute: false));

    $article->refresh();

    expect($article->status)->toBe(ArticleStatusEnum::Withdrawn)
        ->and($article->withdrawn_at)->not->toBeNull();

    $this->actingAs($user)->get(route('articles.edit', $article))->assertOk();
});

test('regular users can not access admin review routes', function () {
    $user = User::factory()->create([
        'role' => UserRole::User,
    ]);

    $this->actingAs($user)->get(route('admin.articles.reviews.index'))->assertForbidden();
});

test('admins can approve pending articles and publish them', function () {
    $user = User::factory()->create();
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $article = Article::factory()->create([
        'author_id' => $user->id,
    ]);

    $this->actingAs($user)->post(route('articles.submit', $article));

    $this->actingAs($admin)->post(route('admin.articles.approve', $article))->assertRedirect(route('admin.articles.show', $article, absolute: false));

    $action = ArticleReviewAction::query()->where('article_id', $article->id)->firstOrFail();

    $article->refresh();

    expect($article->status)->toBe(ArticleStatusEnum::Published)
        ->and($article->approved_by)->toBe($admin->id)
        ->and($article->approved_at)->not->toBeNull()
        ->and($action->action)->toBe(ArticleReviewActionType::Approve);
});

test('admins can reject articles and authors can revise and resubmit them', function () {
    $user = User::factory()->create();
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $article = Article::factory()->create([
        'author_id' => $user->id,
    ]);

    $this->actingAs($user)->post(route('articles.submit', $article));
    $this->actingAs($admin)->post(route('admin.articles.reject', $article), [
        'reason' => 'Needs clearer sourcing.',
    ])->assertRedirect(route('admin.articles.show', $article, absolute: false));

    $article->refresh();

    expect($article->status)->toBe(ArticleStatusEnum::Rejected)
        ->and($article->rejected_by)->toBe($admin->id)
        ->and($article->rejected_at)->not->toBeNull()
        ->and($article->reject_reason)->toBe('Needs clearer sourcing.');

    $this->actingAs($user)->patch(route('articles.update', $article), articlePayload([
        'title' => 'Revised Article',
        'content' => 'A clearer article body.',
    ]))->assertRedirect(route('articles.show', $article, absolute: false));

    $this->actingAs($user)->post(route('articles.submit', $article))->assertRedirect(route('articles.show', $article, absolute: false));

    $article->refresh();

    expect($article->status)->toBe(ArticleStatusEnum::PendingReview)
        ->and($article->slug)->toBe('revised-article')
        ->and($article->rejected_by)->toBeNull()
        ->and($article->rejected_at)->toBeNull()
        ->and($article->reject_reason)->toBeNull();
});

test('admins can take down published articles', function () {
    $user = User::factory()->create();
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $article = Article::factory()->create([
        'author_id' => $user->id,
    ]);

    $this->actingAs($user)->post(route('articles.submit', $article));
    $this->actingAs($admin)->post(route('admin.articles.approve', $article));

    $this->actingAs($admin)->post(route('admin.articles.take-down', $article), [
        'reason' => 'Outdated content.',
    ])->assertRedirect(route('admin.articles.show', $article, absolute: false));

    $article->refresh();

    expect($article->status)->toBe(ArticleStatusEnum::TakenDown)
        ->and($article->taken_down_at)->not->toBeNull();
});

test('published article pages only show published articles', function () {
    $user = User::factory()->create();
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $article = Article::factory()->create([
        'author_id' => $user->id,
    ]);
    $draft = Article::factory()->create([
        'author_id' => $user->id,
    ]);

    $this->actingAs($user)->post(route('articles.submit', $article));
    $this->actingAs($admin)->post(route('admin.articles.approve', $article));

    $this->get(route('published.articles.show', $article->fresh()->publicRouteParameters()))->assertOk()->assertSee($article->title);
    $this->get(route('published.articles.show', $draft->publicRouteParameters()))->assertNotFound();
});

test('published article pages redirect stale slugs to the canonical url', function () {
    $user = User::factory()->create();
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $article = Article::factory()->create([
        'author_id' => $user->id,
        'title' => 'Original Article',
    ]);

    $this->actingAs($user)->patch(route('articles.update', $article), articlePayload([
        'title' => 'Canonical Article',
    ]))->assertRedirect(route('articles.show', $article, absolute: false));

    $this->actingAs($user)->post(route('articles.submit', $article));
    $this->actingAs($admin)->post(route('admin.articles.approve', $article));

    $article->refresh();

    $this->get(route('published.articles.show', [
        'article' => $article,
        'slug' => 'original-article',
    ]))
        ->assertStatus(301)
        ->assertRedirect(route('published.articles.show', $article->publicRouteParameters(), absolute: false));
});

test('articles can share a slug because public urls include the id', function () {
    $first = Article::factory()->create([
        'title' => 'Shared Title',
    ]);
    $second = Article::factory()->create([
        'title' => 'Shared Title',
    ]);

    expect($first->slug)->toBe($second->slug)
        ->and(route('published.articles.show', $first->publicRouteParameters(), absolute: false))
        ->not->toBe(route('published.articles.show', $second->publicRouteParameters(), absolute: false));
});

test('published article index orders articles by approval time', function () {
    $older = Article::factory()->create([
        'title' => 'Older Published Article',
        'status' => ArticleStatusEnum::Published,
        'approved_at' => now()->subDay(),
    ]);
    $newer = Article::factory()->create([
        'title' => 'Newer Published Article',
        'status' => ArticleStatusEnum::Published,
        'approved_at' => now(),
    ]);

    $this->get(route('published.articles.index'))
        ->assertOk()
        ->assertSeeInOrder([$newer->title, $older->title]);
});

test('published article index searches published articles by title only', function () {
    $matching = Article::factory()->create([
        'title' => 'Laravel Release Checklist',
        'status' => ArticleStatusEnum::Published,
        'approved_at' => now(),
    ]);
    $summaryOnlyMatch = Article::factory()->create([
        'title' => 'Editorial Guidelines',
        'summary' => 'Laravel appears only in this summary.',
        'content' => 'Laravel appears only in this content.',
        'status' => ArticleStatusEnum::Published,
        'approved_at' => now()->subMinute(),
    ]);
    $draftMatch = Article::factory()->create([
        'title' => 'Laravel Draft Notes',
        'status' => ArticleStatusEnum::Draft,
    ]);

    $this->get(route('published.articles.index', ['q' => 'Laravel']))
        ->assertOk()
        ->assertSee($matching->title)
        ->assertDontSee($summaryOnlyMatch->title)
        ->assertDontSee($draftMatch->title);
});
