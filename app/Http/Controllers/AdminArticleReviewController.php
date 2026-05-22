<?php

namespace App\Http\Controllers;

use App\Enums\ArticleStatus;
use App\Models\Article;
use App\Services\ArticleWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminArticleReviewController extends Controller
{
    public function __construct(public ArticleWorkflowService $workflow) {}

    public function index(): View
    {
        $articles = Article::query()
            ->with('author', 'currentStatus')
            ->whereHas('currentStatus', fn ($query) => $query->where('status', ArticleStatus::PendingReview))
            ->latest()
            ->paginate(10);

        return view('admin.articles.reviews.index', [
            'articles' => $articles,
        ]);
    }

    public function show(Article $article): View
    {
        $article->loadMissing('author', 'currentStatus', 'reviewActions.admin');

        return view('admin.articles.show', [
            'article' => $article,
        ]);
    }

    public function approve(Request $request, Article $article): RedirectResponse
    {
        $this->workflow->approve($article, $request->user());

        return redirect()
            ->route('admin.articles.show', $article)
            ->with('status', 'Article approved and published.');
    }

    public function reject(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:5000'],
        ]);

        $this->workflow->reject($article, $request->user(), $validated['reason']);

        return redirect()
            ->route('admin.articles.show', $article)
            ->with('status', 'Article rejected.');
    }

    public function takeDown(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:5000'],
        ]);

        $this->workflow->takeDown($article, $request->user(), $validated['reason'] ?? null);

        return redirect()
            ->route('admin.articles.show', $article)
            ->with('status', 'Article taken down.');
    }
}
