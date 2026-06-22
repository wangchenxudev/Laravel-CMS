<?php

use App\Enums\Article\ArticleStatus;
use App\Models\Article;
use App\Models\ArticleImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake(config('articles.image_disk'));
});

test('authors can attach images when creating an article', function () {
    $user = User::factory()->create();
    $disk = Storage::disk(config('articles.image_disk'));

    $this->actingAs($user)->post(route('articles.store'), [
        'title' => 'Article With Images',
        'summary' => 'A summary.',
        'content' => 'Body content.',
        'images' => [
            UploadedFile::fake()->image('one.jpg'),
            UploadedFile::fake()->image('two.png'),
        ],
    ])->assertRedirect();

    $article = Article::query()->where('title', 'Article With Images')->firstOrFail();

    expect($article->images()->count())->toBe(2)
        ->and($article->images()->pluck('position')->all())->toBe([1, 2]);

    $article->images->each(fn (ArticleImage $image) => $disk->assertExists($image->path));
});

test('non-image uploads are rejected', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('articles.store'), [
        'title' => 'Bad Upload',
        'content' => 'Body content.',
        'images' => [UploadedFile::fake()->create('document.pdf', 100, 'application/pdf')],
    ])->assertSessionHasErrors('images.0');

    expect(Article::query()->count())->toBe(0);
});

test('oversize images are rejected', function () {
    $user = User::factory()->create();
    $maxKb = (int) config('articles.max_image_kb');

    $this->actingAs($user)->post(route('articles.store'), [
        'title' => 'Too Big',
        'content' => 'Body content.',
        'images' => [UploadedFile::fake()->image('huge.jpg')->size($maxKb + 100)],
    ])->assertSessionHasErrors('images.0');

    expect(Article::query()->count())->toBe(0);
});

test('exceeding the image count limit is rejected', function () {
    $user = User::factory()->create();
    $max = (int) config('articles.max_images');

    $images = collect(range(1, $max + 1))
        ->map(fn (int $n) => UploadedFile::fake()->image("img{$n}.jpg"))
        ->all();

    $this->actingAs($user)->post(route('articles.store'), [
        'title' => 'Too Many',
        'content' => 'Body content.',
        'images' => $images,
    ])->assertSessionHasErrors('images');

    expect(Article::query()->count())->toBe(0);
});

test('authors can add and remove images when editing', function () {
    $user = User::factory()->create();
    $disk = Storage::disk(config('articles.image_disk'));
    $article = Article::factory()->create([
        'author_id' => $user->id,
        'status' => ArticleStatus::Draft,
    ]);
    $existing = ArticleImage::factory()->for($article)->create(['position' => 1]);

    $disk->assertExists($existing->path);

    $this->actingAs($user)->patch(route('articles.update', $article), [
        'title' => $article->title,
        'content' => $article->content,
        'images' => [UploadedFile::fake()->image('new.jpg')],
        'remove_images' => [$existing->id],
    ])->assertRedirect(route('articles.show', $article, absolute: false));

    expect(ArticleImage::query()->find($existing->id))->toBeNull()
        ->and($article->images()->count())->toBe(1);

    $disk->assertMissing($existing->path);
});

test('authors can not remove images that belong to another article', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create([
        'author_id' => $user->id,
        'status' => ArticleStatus::Draft,
    ]);
    $other = Article::factory()->create([
        'author_id' => $user->id,
        'status' => ArticleStatus::Draft,
    ]);
    $otherImage = ArticleImage::factory()->for($other)->create();

    $this->actingAs($user)->patch(route('articles.update', $article), [
        'title' => $article->title,
        'content' => $article->content,
        'remove_images' => [$otherImage->id],
    ])->assertSessionHasErrors('remove_images');

    expect(ArticleImage::query()->find($otherImage->id))->not->toBeNull();
});

test('published articles show the cover on the list and the gallery in upload order', function () {
    $article = Article::factory()->create([
        'status' => ArticleStatus::Published,
        'approved_at' => now(),
    ]);
    $first = ArticleImage::factory()->for($article)->create(['position' => 1]);
    $second = ArticleImage::factory()->for($article)->create(['position' => 2]);

    $this->get(route('published.articles.index'))
        ->assertOk()
        ->assertSee($first->path);

    $this->get(route('published.articles.show', $article->publicRouteParameters()))
        ->assertOk()
        ->assertSeeInOrder([$first->path, $second->path]);
});
