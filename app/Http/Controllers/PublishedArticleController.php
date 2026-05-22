<?php

namespace App\Http\Controllers;

use App\Enums\ArticleStatus;
use App\Models\Article;
use Illuminate\View\View;

class PublishedArticleController extends Controller
{
    public function index(): View
    {
        $articles = Article::query()
            ->with('author', 'currentStatus')
            ->whereHas('currentStatus', fn ($query) => $query->where('status', ArticleStatus::Published))
            ->latest()
            ->paginate(10);

        return view('published.articles.index', [
            'articles' => $articles,
        ]);
    }

    public function show(Article $article): View
    {
        $article->loadMissing('author', 'currentStatus');

        abort_unless($article->currentStatus?->status === ArticleStatus::Published, 404);

        return view('published.articles.show', [
            'article' => $article,
        ]);
    }
}
