<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('authenticated users can view settings sections', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/settings');

    $response->assertOk();
    $response->assertSee('Account Information');
    $response->assertSee('Password Security');
    $response->assertSee('grid-cols-[176px_minmax(0,1fr)]', false);
    $response->assertSee('href="#account-information"', false);
    $response->assertSee('href="#password-security"', false);
    $response->assertSee('Recover with email code');
});

test('settings entry is only shown in the user menu', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertOk();
    $response->assertSee('<a href="'.route('settings.edit').'" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Settings</a>', false);

    expect(substr_count($response->getContent(), route('settings.edit')))->toBe(1);
});

test('authenticated users can update their password from settings', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->put('/settings/password', [
        'current_password' => 'password',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertRedirect(route('settings.edit', absolute: false));
    $response->assertSessionHas('status', 'Password updated successfully.');

    $user->refresh();

    expect(Hash::check('new-password', $user->password))->toBeTrue();
});

test('authenticated users can not update their password with the wrong current password', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->put('/settings/password', [
        'current_password' => 'wrong-password',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertSessionHasErrors('current_password');

    $user->refresh();

    expect(Hash::check('password', $user->password))->toBeTrue();
});

test('guests can not update passwords from settings', function () {
    $response = $this->put('/settings/password', [
        'current_password' => 'password',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertRedirect(route('login', absolute: false));
});
