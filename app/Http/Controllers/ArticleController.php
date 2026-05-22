<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\ArticleWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function __construct(public ArticleWorkflowService $workflow) {}

    public function index(Request $request): View
    {
        $articles = Article::query()
            ->with('currentStatus')
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

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Article::class);

        $validated = $this->validateArticle($request);

        $article = Article::query()->create([
            'author_id' => $request->user()->id,
            'title' => $validated['title'],
            'slug' => $this->uniqueSlug($validated['slug'] ?? $validated['title']),
            'summary' => $validated['summary'] ?? null,
            'content' => $validated['content'],
        ]);

        $this->workflow->createDraftStatus($article);

        return redirect()
            ->route('articles.show', $article)
            ->with('status', 'Article created as a draft.');
    }

    public function show(Article $article): View
    {
        $article->loadMissing('currentStatus', 'author');

        Gate::authorize('view', $article);

        return view('articles.show', [
            'article' => $article,
        ]);
    }

    public function edit(Article $article): View
    {
        $article->loadMissing('currentStatus');

        Gate::authorize('update', $article);

        return view('articles.edit', [
            'article' => $article,
        ]);
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $article->loadMissing('currentStatus');

        Gate::authorize('update', $article);

        $validated = $this->validateArticle($request, $article);

        $article->update([
            'title' => $validated['title'],
            'slug' => $this->uniqueSlug($validated['slug'] ?? $validated['title'], $article),
            'summary' => $validated['summary'] ?? null,
            'content' => $validated['content'],
        ]);

        return redirect()
            ->route('articles.show', $article)
            ->with('status', 'Article updated.');
    }

    public function submit(Article $article): RedirectResponse
    {
        $article->loadMissing('currentStatus');

        Gate::authorize('submit', $article);

        $this->workflow->submit($article);

        return redirect()
            ->route('articles.show', $article)
            ->with('status', 'Article submitted for review.');
    }

    public function withdraw(Article $article): RedirectResponse
    {
        $article->loadMissing('currentStatus');

        Gate::authorize('withdraw', $article);

        $this->workflow->withdraw($article);

        return redirect()
            ->route('articles.show', $article)
            ->with('status', 'Article withdrawn from review.');
    }

    /**
     * @return array{title: string, slug?: string|null, summary?: string|null, content: string}
     */
    private function validateArticle(Request $request, ?Article $article = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('articles', 'slug')->ignore($article),
            ],
            'summary' => ['nullable', 'string'],
            'content' => ['required', 'string'],
        ]);
    }

    private function uniqueSlug(string $value, ?Article $except = null): string
    {
        $base = Str::slug($value) ?: 'article';
        $slug = $base;
        $suffix = 2;

        while (Article::query()
            ->where('slug', $slug)
            ->when($except, fn ($query) => $query->whereKeyNot($except->id))
            ->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
