<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ArticleStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRejectRequest;
use App\Http\Requests\ReviewTakeDownRequest;
use App\Models\Article;
use App\Services\ArticleWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AdminArticleReviewController extends Controller
{
    public function __construct(public ArticleWorkflowService $workflow) {}

    public function index(): View
    {
        $articles = Article::query()
            ->with('author')
            ->where('status', ArticleStatus::PendingReview)
            ->latest()
            ->paginate(10);

        return view('admin.articles.reviews.index', [
            'articles' => $articles,
        ]);
    }

    public function show(Article $article): View
    {
        Gate::authorize('view', $article);

        $article->loadMissing('author', 'reviewActions.admin');

        return view('admin.articles.show', [
            'article' => $article,
        ]);
    }

    public function approve(Request $request, Article $article): RedirectResponse
    {
        Gate::authorize('approve', $article);

        $this->workflow->approve($article, $request->user());

        return redirect()
            ->route('admin.articles.show', $article)
            ->with('status', 'Article approved and published.');
    }

    public function reject(ReviewRejectRequest $request, Article $article): RedirectResponse
    {
        $validated = $request->validated();

        $this->workflow->reject($article, $request->user(), $validated['reason']);

        return redirect()
            ->route('admin.articles.show', $article)
            ->with('status', 'Article rejected.');
    }

    public function takeDown(ReviewTakeDownRequest $request, Article $article): RedirectResponse
    {
        $validated = $request->validated();

        $this->workflow->takeDown($article, $request->user(), $validated['reason'] ?? null);

        return redirect()
            ->route('admin.articles.show', $article)
            ->with('status', 'Article taken down.');
    }
}
