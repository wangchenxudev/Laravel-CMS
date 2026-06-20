<?php

use App\Http\Controllers\Cms\ArticleController;
use App\Http\Controllers\Cms\PublishedArticleController;
use Illuminate\Support\Facades\Route;

Route::get('/published/articles', [PublishedArticleController::class, 'index'])->name('published.articles.index');

Route::get('/published/articles/{article}-{slug}', [PublishedArticleController::class, 'show'])
    ->whereNumber('article')
    ->where('slug', '[A-Za-z0-9-]+')
    ->name('published.articles.show');

Route::middleware('auth')->group(function (): void {
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::patch('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::post('/articles/{article}/submit', [ArticleController::class, 'submit'])->name('articles.submit');
    Route::post('/articles/{article}/withdraw', [ArticleController::class, 'withdraw'])->name('articles.withdraw');
});
