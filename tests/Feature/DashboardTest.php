<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated users can navigate from home to dashboard', function () {
    $user = User::factory()->create([
        'role' => UserRole::User,
    ]);

    $response = $this->actingAs($user)->get('/');

    $response->assertOk();
    $response->assertSee('Dashboard');
});

test('admin users can navigate from home to user and admin dashboards', function () {
    $user = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $response = $this->actingAs($user)->get('/');

    $response->assertOk();
    $response->assertSee('Dashboard');
    $response->assertSee('Admin Console');
});

test('guests are redirected from dashboard to login', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect(route('login', absolute: false));
});

test('authenticated users can view dashboard with their role', function () {
    $user = User::factory()->create([
        'role' => UserRole::User,
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertOk();
    $response->assertSee('Current role');
    $response->assertSee('user');
});

test('guests are redirected from admin dashboard to login', function () {
    $response = $this->get('/admin/dashboard');

    $response->assertRedirect(route('login', absolute: false));
});

test('regular users can not view admin dashboard', function () {
    $user = User::factory()->create([
        'role' => UserRole::User,
    ]);

    $response = $this->actingAs($user)->get('/admin/dashboard');

    $response->assertForbidden();
});

test('admin users can view admin dashboard', function () {
    $user = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $response = $this->actingAs($user)->get('/admin/dashboard');

    $response->assertOk();
    $response->assertSee('Admin dashboard');
    $response->assertSee('admin');
});
