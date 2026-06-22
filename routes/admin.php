<?php

use App\Http\Controllers\Admin\AdminArticleReviewController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminTagController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function (): void {
    Route::get('/dashboard', AdminDashboardController::class)->name('admin.dashboard');

    Route::resource('tags', AdminTagController::class)->except(['show'])->names('admin.tags');

    Route::get('/articles/reviews', [AdminArticleReviewController::class, 'index'])->name('admin.articles.reviews.index');
    Route::get('/articles/{article}', [AdminArticleReviewController::class, 'show'])->name('admin.articles.show');
    Route::post('/articles/{article}/approve', [AdminArticleReviewController::class, 'approve'])->name('admin.articles.approve');
    Route::post('/articles/{article}/reject', [AdminArticleReviewController::class, 'reject'])->name('admin.articles.reject');
    Route::post('/articles/{article}/take-down', [AdminArticleReviewController::class, 'takeDown'])->name('admin.articles.take-down');
});
