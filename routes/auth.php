<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetCodeController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\SettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/register/verify', [RegisteredUserController::class, 'verify'])->name('register.verify');
    Route::post('/register/verify', [RegisteredUserController::class, 'confirm'])->name('register.confirm');
    Route::post('/register/verify/resend', [RegisteredUserController::class, 'resend'])->name('register.verify.resend');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

});

Route::get('/forgot-password', [PasswordResetCodeController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetCodeController::class, 'store'])->name('password.email');
Route::get('/reset-password', [PasswordResetCodeController::class, 'edit'])->name('password.reset');
Route::post('/reset-password', [PasswordResetCodeController::class, 'update'])->name('password.update');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
});
