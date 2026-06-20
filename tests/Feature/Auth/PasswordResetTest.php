<?php

use App\Models\User;
use App\Notifications\Auth\PasswordResetCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

test('forgot password screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertOk();
});

test('users can request a password reset code', function () {
    Notification::fake();

    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $response = $this->post('/forgot-password', [
        'email' => 'test@example.com',
    ]);

    $response->assertRedirect(route('password.reset', absolute: false));

    $resetCode = null;

    Notification::assertSentTo($user, PasswordResetCode::class, function (PasswordResetCode $notification) use (&$resetCode): bool {
        $resetCode = $notification->code;

        return preg_match('/^\d{6}$/', $notification->code) === 1;
    });

    $tokenRecord = DB::table('password_reset_tokens')->where('email', 'test@example.com')->first();

    expect($tokenRecord)->not->toBeNull()
        ->and($tokenRecord->token)->not->toBe($resetCode)
        ->and(Hash::check($resetCode, $tokenRecord->token))->toBeTrue();
});

test('unknown emails receive the same password reset response without a notification', function () {
    Notification::fake();

    $response = $this->post('/forgot-password', [
        'email' => 'missing@example.com',
    ]);

    $response->assertRedirect(route('password.reset', absolute: false));
    $response->assertSessionHas('status', 'If an account exists for that email, a reset code has been sent.');

    Notification::assertNothingSent();
    $this->assertDatabaseMissing('password_reset_tokens', [
        'email' => 'missing@example.com',
    ]);
});

test('users can reset their password with a valid code', function () {
    Notification::fake();

    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $this->post('/forgot-password', [
        'email' => 'test@example.com',
    ]);

    $resetCode = null;

    Notification::assertSentTo($user, PasswordResetCode::class, function (PasswordResetCode $notification) use (&$resetCode): bool {
        $resetCode = $notification->code;

        return true;
    });

    $response = $this->post('/reset-password', [
        'email' => 'test@example.com',
        'code' => $resetCode,
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertRedirect(route('login', absolute: false));

    $user->refresh();

    expect(Hash::check('new-password', $user->password))->toBeTrue();
    $this->assertDatabaseMissing('password_reset_tokens', [
        'email' => 'test@example.com',
    ]);
});

test('authenticated users are returned to settings after password reset code succeeds', function () {
    Notification::fake();

    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $this->actingAs($user)->post('/forgot-password', [
        'email' => 'test@example.com',
    ]);

    $resetCode = null;

    Notification::assertSentTo($user, PasswordResetCode::class, function (PasswordResetCode $notification) use (&$resetCode): bool {
        $resetCode = $notification->code;

        return true;
    });

    $response = $this->actingAs($user)->post('/reset-password', [
        'email' => 'test@example.com',
        'code' => $resetCode,
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertRedirect(route('settings.edit', absolute: false));

    $user->refresh();

    expect(Hash::check('new-password', $user->password))->toBeTrue();
});

test('users can not reset their password with an invalid code', function () {
    Notification::fake();

    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $this->post('/forgot-password', [
        'email' => 'test@example.com',
    ]);

    $response = $this->post('/reset-password', [
        'email' => 'test@example.com',
        'code' => '000000',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertSessionHasErrors('code');

    $user->refresh();

    expect(Hash::check('password', $user->password))->toBeTrue();
    $this->assertDatabaseHas('password_reset_tokens', [
        'email' => 'test@example.com',
    ]);
});

test('users can not reset their password with an expired code', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    DB::table('password_reset_tokens')->insert([
        'email' => 'test@example.com',
        'token' => Hash::make('123456'),
        'created_at' => now()->subMinutes(61),
    ]);

    $response = $this->post('/reset-password', [
        'email' => 'test@example.com',
        'code' => '123456',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertSessionHasErrors('code');

    $user->refresh();

    expect(Hash::check('password', $user->password))->toBeTrue();
    $this->assertDatabaseHas('password_reset_tokens', [
        'email' => 'test@example.com',
    ]);
});
