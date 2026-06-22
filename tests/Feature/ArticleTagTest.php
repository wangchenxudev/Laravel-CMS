<?php

use App\Enums\Article\ArticleStatus;
use App\Enums\User\UserRole;
use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authors can attach existing tags when creating an article', function () {
    $user = User::factory()->create();
    $tags = Tag::factory()->count(3)->create();

    $this->actingAs($user)->post(route('articles.store'), [
        'title' => 'Tagged Article',
        'content' => 'Body content.',
        'tags' => $tags->pluck('id')->all(),
    ])->assertRedirect();

    $article = Article::query()->where('title', 'Tagged Article')->firstOrFail();

    expect($article->tags)->toHaveCount(3);
});

test('an article can not have more than the configured maximum tags', function () {
    config(['articles.max_tags' => 5]);

    $user = User::factory()->create();
    $tags = Tag::factory()->count(6)->create();

    $this->actingAs($user)->post(route('articles.store'), [
        'title' => 'Too Many Tags',
        'content' => 'Body content.',
        'tags' => $tags->pluck('id')->all(),
    ])->assertSessionHasErrors('tags');

    expect(Article::query()->where('title', 'Too Many Tags')->exists())->toBeFalse();
});

test('only existing tags can be attached to an article', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('articles.store'), [
        'title' => 'Bad Tag',
        'content' => 'Body content.',
        'tags' => [99999],
    ])->assertSessionHasErrors('tags.0');

    expect(Article::query()->where('title', 'Bad Tag')->exists())->toBeFalse();
});

test('updating an article syncs its tags', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create([
        'author_id' => $user->id,
        'status' => ArticleStatus::Draft,
    ]);
    $original = Tag::factory()->count(2)->create();
    $article->tags()->sync($original->pluck('id')->all());

    $replacement = Tag::factory()->create();

    $this->actingAs($user)->patch(route('articles.update', $article), [
        'title' => $article->title,
        'content' => $article->content,
        'tags' => [$replacement->id],
    ])->assertRedirect(route('articles.show', $article, absolute: false));

    expect($article->fresh()->tags->pluck('id')->all())->toBe([$replacement->id]);
});

test('authors can change tags on their published article', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create([
        'author_id' => $user->id,
        'status' => ArticleStatus::Published,
        'approved_at' => now(),
    ]);
    $tags = Tag::factory()->count(2)->create();

    $this->actingAs($user)->patch(route('articles.tags.update', $article), [
        'tags' => $tags->pluck('id')->all(),
    ])->assertRedirect();

    expect($article->fresh()->tags)->toHaveCount(2);
});

test('admins can change tags while reviewing a pending article', function () {
    $author = User::factory()->create();
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $article = Article::factory()->create([
        'author_id' => $author->id,
        'status' => ArticleStatus::PendingReview,
    ]);
    $tags = Tag::factory()->count(2)->create();

    $this->actingAs($admin)->patch(route('articles.tags.update', $article), [
        'tags' => $tags->pluck('id')->all(),
    ])->assertRedirect();

    expect($article->fresh()->tags)->toHaveCount(2);
});

test('users can not change tags on articles they do not own', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $article = Article::factory()->create([
        'author_id' => $owner->id,
        'status' => ArticleStatus::Published,
        'approved_at' => now(),
    ]);
    $tag = Tag::factory()->create();

    $this->actingAs($other)->patch(route('articles.tags.update', $article), [
        'tags' => [$tag->id],
    ])->assertForbidden();

    expect($article->fresh()->tags)->toHaveCount(0);
});

test('tags can not be changed once an article is taken down', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create([
        'author_id' => $user->id,
        'status' => ArticleStatus::TakenDown,
        'taken_down_at' => now(),
    ]);
    $tag = Tag::factory()->create();

    $this->actingAs($user)->patch(route('articles.tags.update', $article), [
        'tags' => [$tag->id],
    ])->assertForbidden();
});

test('the max tag limit is enforced when editing tags directly', function () {
    config(['articles.max_tags' => 5]);

    $user = User::factory()->create();
    $article = Article::factory()->create([
        'author_id' => $user->id,
        'status' => ArticleStatus::Published,
        'approved_at' => now(),
    ]);
    $tags = Tag::factory()->count(6)->create();

    $this->actingAs($user)->patch(route('articles.tags.update', $article), [
        'tags' => $tags->pluck('id')->all(),
    ])->assertSessionHasErrors('tags');

    expect($article->fresh()->tags)->toHaveCount(0);
});

test('the published index can filter articles by tag', function () {
    $tag = Tag::factory()->create(['name' => 'Laravel', 'slug' => 'laravel']);

    $tagged = Article::factory()->create([
        'title' => 'Tagged Published Article',
        'status' => ArticleStatus::Published,
        'approved_at' => now(),
    ]);
    $tagged->tags()->sync([$tag->id]);

    $untagged = Article::factory()->create([
        'title' => 'Untagged Published Article',
        'status' => ArticleStatus::Published,
        'approved_at' => now(),
    ]);

    $this->get(route('published.articles.index', ['tag' => 'laravel']))
        ->assertOk()
        ->assertSee($tagged->title)
        ->assertDontSee($untagged->title);
});
