<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\StoreArticleRequest;
use App\Http\Requests\Cms\UpdateArticleRequest;
use App\Http\Requests\Cms\UpdateArticleTagsRequest;
use App\Models\Article;
use App\Models\Tag;
use App\Services\ArticleImageService;
use App\Services\ArticleWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function __construct(
        public ArticleWorkflowService $workflow,
        public ArticleImageService $images,
    ) {}

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

        return view('articles.create', [
            'tags' => Tag::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreArticleRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $article = Article::query()->create([
            'author_id' => $request->user()->id,
            'title' => $validated['title'],
            'summary' => $validated['summary'] ?? null,
            'content' => $validated['content'],
        ]);

        $article->tags()->sync($validated['tags'] ?? []);

        $this->images->store($article, $request->file('images', []));

        return redirect()
            ->route('articles.show', $article)
            ->with('status', 'Article created as a draft.');
    }

    public function show(Article $article): View
    {
        Gate::authorize('view', $article);

        $article->loadMissing('author', 'images', 'tags');

        return view('articles.show', [
            'article' => $article,
            'tags' => Tag::query()->orderBy('name')->get(),
        ]);
    }

    public function edit(Article $article): View
    {
        Gate::authorize('update', $article);

        $article->loadMissing('images', 'tags');

        return view('articles.edit', [
            'article' => $article,
            'tags' => Tag::query()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        $validated = $request->validated();

        $article->update([
            'title' => $validated['title'],
            'summary' => $validated['summary'] ?? null,
            'content' => $validated['content'],
        ]);

        $article->tags()->sync($validated['tags'] ?? []);

        $this->images->sync(
            $article,
            $request->file('images', []),
            array_map('intval', $validated['remove_images'] ?? []),
        );

        return redirect()
            ->route('articles.show', $article)
            ->with('status', 'Article updated.');
    }

    public function updateTags(UpdateArticleTagsRequest $request, Article $article): RedirectResponse
    {
        $validated = $request->validated();

        $article->tags()->sync($validated['tags'] ?? []);

        return back()->with('status', 'Tags updated.');
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

    public function destroy(Article $article): RedirectResponse
    {
        Gate::authorize('delete', $article);

        $article->delete();

        return redirect()
            ->route('articles.index')
            ->with('status', 'Article deleted.');
    }
}
