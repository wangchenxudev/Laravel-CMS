<?php

use App\Enums\User\UserRole;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function adminUser(): User
{
    return User::factory()->create([
        'role' => UserRole::Admin,
    ]);
}

test('admins can view the tag management index', function () {
    Tag::factory()->create(['name' => 'Laravel']);

    $this->actingAs(adminUser())
        ->get(route('admin.tags.index'))
        ->assertOk()
        ->assertSee('Laravel');
});

test('admins can create a tag with an auto generated slug', function () {
    $this->actingAs(adminUser())
        ->post(route('admin.tags.store'), ['name' => 'Hello World'])
        ->assertRedirect(route('admin.tags.index', absolute: false));

    $tag = Tag::query()->where('name', 'Hello World')->firstOrFail();

    expect($tag->slug)->toBe('hello-world');
});

test('tag names must be unique', function () {
    Tag::factory()->create(['name' => 'Laravel', 'slug' => 'laravel']);

    $this->actingAs(adminUser())
        ->post(route('admin.tags.store'), ['name' => 'Laravel'])
        ->assertSessionHasErrors('name');

    expect(Tag::query()->where('name', 'Laravel')->count())->toBe(1);
});

test('admins can rename a tag and the slug follows', function () {
    $tag = Tag::factory()->create(['name' => 'Old Name', 'slug' => 'old-name']);

    $this->actingAs(adminUser())
        ->patch(route('admin.tags.update', $tag), ['name' => 'New Name'])
        ->assertRedirect(route('admin.tags.index', absolute: false));

    $tag->refresh();

    expect($tag->name)->toBe('New Name')
        ->and($tag->slug)->toBe('new-name');
});

test('admins can delete a tag', function () {
    $tag = Tag::factory()->create();

    $this->actingAs(adminUser())
        ->delete(route('admin.tags.destroy', $tag))
        ->assertRedirect(route('admin.tags.index', absolute: false));

    expect(Tag::query()->find($tag->id))->toBeNull();
});

test('regular users can not access tag management', function () {
    $user = User::factory()->create([
        'role' => UserRole::User,
    ]);

    $this->actingAs($user)->get(route('admin.tags.index'))->assertForbidden();
    $this->actingAs($user)->post(route('admin.tags.store'), ['name' => 'Sneaky'])->assertForbidden();

    expect(Tag::query()->where('name', 'Sneaky')->exists())->toBeFalse();
});

test('guests can not access tag management', function () {
    $this->get(route('admin.tags.index'))->assertRedirect(route('login', absolute: false));
});
