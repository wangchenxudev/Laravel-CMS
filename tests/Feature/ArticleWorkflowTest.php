<?php

use App\Enums\ArticleReviewActionType;
use App\Enums\ArticleStatus as ArticleStatusEnum;
use App\Enums\UserRole;
use App\Models\Article;
use App\Models\ArticleReviewAction;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function articlePayload(array $overrides = []): array
{
    return array_merge([
        'title' => 'Reviewable Article',
        'slug' => 'reviewable-article',
        'summary' => 'A short summary.',
        'content' => 'A complete article body.',
    ], $overrides);
}

test('authenticated users can create draft articles tied to their user id', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('articles.store'), articlePayload());

    $article = Article::query()->where('slug', 'reviewable-article')->firstOrFail();

    $response->assertRedirect(route('articles.show', $article, absolute: false));

    expect($article->author_id)->toBe($user->id)
        ->and($article->currentStatus->status)->toBe(ArticleStatusEnum::Draft);
});

test('guests can not create articles', function () {
    $response = $this->post(route('articles.store'), articlePayload());

    $response->assertRedirect(route('login', absolute: false));
});

test('users can only view and edit their own articles', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $article = Article::factory()->create([
        'author_id' => $owner->id,
    ]);

    $this->actingAs($otherUser)->get(route('articles.show', $article))->assertForbidden();
    $this->actingAs($otherUser)->get(route('articles.edit', $article))->assertForbidden();
});

test('users can submit and withdraw pending articles', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create([
        'author_id' => $user->id,
    ]);

    $this->actingAs($user)->post(route('articles.submit', $article))->assertRedirect(route('articles.show', $article, absolute: false));

    expect($article->currentStatus()->first()->status)->toBe(ArticleStatusEnum::PendingReview);

    $this->actingAs($user)->get(route('articles.edit', $article))->assertForbidden();

    $this->actingAs($user)->post(route('articles.withdraw', $article))->assertRedirect(route('articles.show', $article, absolute: false));

    $status = $article->currentStatus()->first();

    expect($status->status)->toBe(ArticleStatusEnum::Withdrawn)
        ->and($status->withdrawn_at)->not->toBeNull();

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

    $status = $article->currentStatus()->first();
    $action = ArticleReviewAction::query()->where('article_id', $article->id)->firstOrFail();

    expect($status->status)->toBe(ArticleStatusEnum::Published)
        ->and($status->approved_by)->toBe($admin->id)
        ->and($status->approved_at)->not->toBeNull()
        ->and($action->action)->toBe(ArticleReviewActionType::Approve)
        ->and($action->is_open)->toBeFalse()
        ->and($action->open_slot)->toBeNull();
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

    $status = $article->currentStatus()->first();

    expect($status->status)->toBe(ArticleStatusEnum::Rejected)
        ->and($status->rejected_by)->toBe($admin->id)
        ->and($status->rejected_at)->not->toBeNull()
        ->and($status->reject_reason)->toBe('Needs clearer sourcing.');

    $this->actingAs($user)->patch(route('articles.update', $article), articlePayload([
        'title' => 'Revised Article',
        'slug' => 'revised-article',
        'content' => 'A clearer article body.',
    ]))->assertRedirect(route('articles.show', $article, absolute: false));

    $this->actingAs($user)->post(route('articles.submit', $article))->assertRedirect(route('articles.show', $article, absolute: false));

    $status = $article->currentStatus()->first();

    expect($status->status)->toBe(ArticleStatusEnum::PendingReview)
        ->and($status->rejected_by)->toBeNull()
        ->and($status->rejected_at)->toBeNull()
        ->and($status->reject_reason)->toBeNull();
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

    $status = $article->currentStatus()->first();

    expect($status->status)->toBe(ArticleStatusEnum::TakenDown)
        ->and($status->taken_down_at)->not->toBeNull();
});

test('published article pages only show published articles', function () {
    $user = User::factory()->create();
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $article = Article::factory()->create([
        'author_id' => $user->id,
        'slug' => 'public-article',
    ]);
    $draft = Article::factory()->create([
        'author_id' => $user->id,
        'slug' => 'draft-article',
    ]);

    $this->actingAs($user)->post(route('articles.submit', $article));
    $this->actingAs($admin)->post(route('admin.articles.approve', $article));

    $this->get(route('published.articles.show', $article))->assertOk()->assertSee($article->title);
    $this->get(route('published.articles.show', $draft))->assertNotFound();
});

test('only one open review action can exist for an article', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $article = Article::factory()->create();

    ArticleReviewAction::query()->create([
        'article_id' => $article->id,
        'admin_id' => $admin->id,
        'action' => ArticleReviewActionType::Approve,
        'from_status' => ArticleStatusEnum::PendingReview,
        'to_status' => ArticleStatusEnum::Published,
        'is_open' => true,
        'open_slot' => 'open',
    ]);

    expect(fn () => ArticleReviewAction::query()->create([
        'article_id' => $article->id,
        'admin_id' => $admin->id,
        'action' => ArticleReviewActionType::Reject,
        'from_status' => ArticleStatusEnum::PendingReview,
        'to_status' => ArticleStatusEnum::Rejected,
        'is_open' => true,
        'open_slot' => 'open',
    ]))->toThrow(QueryException::class);
});
