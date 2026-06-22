<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTagRequest;
use App\Http\Requests\Admin\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AdminTagController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Tag::class);

        $tags = Tag::query()
            ->withCount('articles')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.tags.index', [
            'tags' => $tags,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Tag::class);

        return view('admin.tags.create');
    }

    public function store(StoreTagRequest $request): RedirectResponse
    {
        Tag::query()->create($request->validated());

        return redirect()
            ->route('admin.tags.index')
            ->with('status', 'Tag created.');
    }

    public function edit(Tag $tag): View
    {
        Gate::authorize('update', $tag);

        return view('admin.tags.edit', [
            'tag' => $tag,
        ]);
    }

    public function update(UpdateTagRequest $request, Tag $tag): RedirectResponse
    {
        $tag->update($request->validated());

        return redirect()
            ->route('admin.tags.index')
            ->with('status', 'Tag updated.');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        Gate::authorize('delete', $tag);

        $tag->delete();

        return redirect()
            ->route('admin.tags.index')
            ->with('status', 'Tag deleted.');
    }
}
