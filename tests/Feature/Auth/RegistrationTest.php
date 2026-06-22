<?php

use App\Enums\User\UserRole;
use App\Models\User;
use App\Notifications\Auth\RegistrationVerificationCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertOk();
});

test('new users receive a verification code before account creation', function () {
    Notification::fake();

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('register.verify', absolute: false));
    $this->assertGuest();
    $this->assertDatabaseMissing('users', [
        'email' => 'test@example.com',
    ]);

    Notification::assertSentOnDemand(RegistrationVerificationCode::class, function (RegistrationVerificationCode $notification, array $channels, object $notifiable): bool {
        return preg_match('/^\d{6}$/', $notification->code) === 1
            && $channels === ['mail']
            && $notifiable->routes['mail'] === 'test@example.com';
    });
});

test('new users can verify email and create a regular account', function () {
    Notification::fake();

    $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $verificationCode = null;

    Notification::assertSentOnDemand(RegistrationVerificationCode::class, function (RegistrationVerificationCode $notification) use (&$verificationCode): bool {
        $verificationCode = $notification->code;

        return true;
    });

    $response = $this->post('/register/verify', [
        'email' => 'test@example.com',
        'code' => $verificationCode,
    ]);

    $user = User::query()->where('email', 'test@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->role)->toBe(UserRole::User)
        ->and($user->email_verified_at)->not->toBeNull()
        ->and(Hash::check('password', $user->password))->toBeTrue();

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('published.articles.index', absolute: false));
});

test('new users can not create an account with an invalid verification code', function () {
    Notification::fake();

    $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response = $this->post('/register/verify', [
        'email' => 'test@example.com',
        'code' => '000000',
    ]);

    $response->assertSessionHasErrors('code');
    $this->assertGuest();
    $this->assertDatabaseMissing('users', [
        'email' => 'test@example.com',
    ]);
});

test('new users can not create an account with an expired verification code', function () {
    Notification::fake();

    $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $verificationCode = null;

    Notification::assertSentOnDemand(RegistrationVerificationCode::class, function (RegistrationVerificationCode $notification) use (&$verificationCode): bool {
        $verificationCode = $notification->code;

        return true;
    });

    Cache::flush();

    $response = $this->post('/register/verify', [
        'email' => 'test@example.com',
        'code' => $verificationCode,
    ]);

    $response->assertSessionHasErrors('code');
    $this->assertGuest();
    $this->assertDatabaseMissing('users', [
        'email' => 'test@example.com',
    ]);
});
