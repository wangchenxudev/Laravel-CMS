<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Services\ArticleWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function __construct(public ArticleWorkflowService $workflow) {}

    public function index(Request $request): View
    {
        $articles = Article::query()
            ->where('author_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('articles.index', [
            'articles' => $articles,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Article::class);

        return view('articles.create');
    }

    public function store(StoreArticleRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $article = Article::query()->create([
            'author_id' => $request->user()->id,
            'title' => $validated['title'],
            'slug' => $this->slugForTitle($validated['title']),
            'summary' => $validated['summary'] ?? null,
            'content' => $validated['content'],
        ]);

        return redirect()
            ->route('articles.show', $article)
            ->with('status', 'Article created as a draft.');
    }

    public function show(Article $article): View
    {
        Gate::authorize('view', $article);

        $article->loadMissing('author');

        return view('articles.show', [
            'article' => $article,
        ]);
    }

    public function edit(Article $article): View
    {
        Gate::authorize('update', $article);

        return view('articles.edit', [
            'article' => $article,
        ]);
    }

    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        $validated = $request->validated();

        $article->update([
            'title' => $validated['title'],
            'slug' => $this->slugForTitle($validated['title']),
            'summary' => $validated['summary'] ?? null,
            'content' => $validated['content'],
        ]);

        return redirect()
            ->route('articles.show', $article)
            ->with('status', 'Article updated.');
    }

    public function submit(Article $article): RedirectResponse
    {
        Gate::authorize('submit', $article);

        $this->workflow->submit($article);

        return redirect()
            ->route('articles.show', $article)
            ->with('status', 'Article submitted for review.');
    }

    public function withdraw(Article $article): RedirectResponse
    {
        Gate::authorize('withdraw', $article);

        $this->workflow->withdraw($article);

        return redirect()
            ->route('articles.show', $article)
            ->with('status', 'Article withdrawn from review.');
    }

    private function slugForTitle(string $title): string
    {
        return Str::slug($title) ?: 'article';
    }
}
