<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected from dashboard to login', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect(route('login', absolute: false));
});

test('authenticated users can view dashboard with their role', function () {
    $user = User::factory()->create([
        'role' => 'user',
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertOk();
    $response->assertSee('Current role');
    $response->assertSee('user');
});
