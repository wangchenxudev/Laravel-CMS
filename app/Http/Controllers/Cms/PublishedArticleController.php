<?php

namespace App\Http\Controllers\Cms;

use App\Enums\ArticleStatus;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublishedArticleController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $articles = Article::query()
            ->with('author')
            ->where('status', ArticleStatus::Published)
            ->when($search !== '', fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->orderByDesc('approved_at')
            ->paginate(10)
            ->withQueryString();

        return view('published.articles.index', [
            'articles' => $articles,
            'search' => $search,
        ]);
    }

    public function show(Article $article, string $slug): RedirectResponse|View
    {
        $article->loadMissing('author');

        abort_unless($article->status === ArticleStatus::Published, 404);

        if ($slug !== $article->slug) {
            return redirect()
                ->route('published.articles.show', $article->publicRouteParameters(), 301);
        }

        return view('published.articles.show', [
            'article' => $article,
        ]);
    }
}
