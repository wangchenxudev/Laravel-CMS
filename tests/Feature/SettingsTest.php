<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated users can view settings', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/settings');

    $response->assertOk();
    $response->assertSee('Admin upgrade request');
});

test('regular users can submit an admin upgrade request with the invitation code', function () {
    $user = User::factory()->create([
        'role' => 'user',
    ]);

    $response = $this->actingAs($user)->post('/settings/admin-upgrade-request', [
        'invitation_code' => '123456',
    ]);

    $response->assertRedirect();

    $user->refresh();

    expect($user->admin_upgrade_requested_at)->not->toBeNull()
        ->and($user->role)->toBe('user');
});

test('regular users can not submit an admin upgrade request with an invalid invitation code', function () {
    $user = User::factory()->create([
        'role' => 'user',
    ]);

    $response = $this->actingAs($user)->post('/settings/admin-upgrade-request', [
        'invitation_code' => 'wrong-code',
    ]);

    $response->assertSessionHasErrors('invitation_code');

    expect($user->refresh()->admin_upgrade_requested_at)->toBeNull();
});

test('admin users can not submit admin upgrade requests', function () {
    $user = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this->actingAs($user)->post('/settings/admin-upgrade-request', [
        'invitation_code' => '123456',
    ]);

    $response->assertForbidden();
});
