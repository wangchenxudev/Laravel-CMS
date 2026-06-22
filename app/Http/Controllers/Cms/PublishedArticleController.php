<?php

namespace App\Http\Controllers\Cms;

use App\Enums\Article\ArticleStatus;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublishedArticleController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $tagSlug = trim((string) $request->query('tag', ''));

        $activeTag = $tagSlug !== ''
            ? Tag::query()->where('slug', $tagSlug)->first()
            : null;

        $articles = Article::query()
            ->with('author', 'coverImage', 'tags')
            ->where('status', ArticleStatus::Published)
            ->when($search !== '', fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->when($activeTag, fn ($query) => $query->whereHas('tags', fn ($tagQuery) => $tagQuery->whereKey($activeTag->id)))
            ->orderByDesc('approved_at')
            ->paginate(10)
            ->withQueryString();

        return view('published.articles.index', [
            'articles' => $articles,
            'search' => $search,
            'filterTags' => Tag::query()->orderBy('name')->get(),
            'activeTag' => $activeTag,
        ]);
    }

    public function show(Article $article, string $slug): RedirectResponse|View
    {
        abort_unless($article->status === ArticleStatus::Published, 404);

        $article->loadMissing('author', 'images', 'tags');

        if ($slug !== $article->slug) {
            return redirect()
                ->route('published.articles.show', $article->publicRouteParameters(), 301);
        }

        $moreArticles = Article::query()
            ->with('author', 'tags')
            ->where('status', ArticleStatus::Published)
            ->whereKeyNot($article->getKey())
            ->orderByDesc('approved_at')
            ->limit(8)
            ->get();

        return view('published.articles.show', [
            'article' => $article,
            'moreArticles' => $moreArticles,
        ]);
    }
}
