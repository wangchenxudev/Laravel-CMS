<x-app-layout title="My Articles">
  <div class="mx-auto max-w-6xl px-6 py-10">
    <div class="flex items-center justify-between border-b border-zinc-200 pb-6">
      <div>
        <h1 class="text-3xl font-semibold tracking-tight text-zinc-950">My Articles</h1>
        <p class="mt-2 text-sm text-zinc-600">Create drafts, submit them for review, and track publishing status.</p>
      </div>
      <a href="{{ route('articles.create') }}" class="rounded-sm bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700">
        New Article
      </a>
    </div>

    <div class="mt-6 overflow-hidden rounded-sm border border-zinc-200 bg-white">
      @forelse ($articles as $article)
        <a href="{{ route('articles.show', $article) }}" class="block border-b border-zinc-100 px-5 py-4 last:border-b-0 hover:bg-zinc-50">
          <div class="flex items-center justify-between gap-4">
            <div>
              <h2 class="font-semibold text-zinc-950">{{ $article->title }}</h2>
              <p class="mt-1 text-sm text-zinc-600">{{ $article->summary }}</p>
            </div>
            <span class="shrink-0 rounded-sm border border-zinc-200 px-2 py-1 text-xs font-medium text-zinc-700">
              {{ $article->currentStatus?->status->value }}
            </span>
          </div>
        </a>
      @empty
        <div class="px-5 py-10 text-sm text-zinc-600">No articles yet.</div>
      @endforelse
    </div>

    <div class="mt-6">
      {{ $articles->links() }}
    </div>
  </div>
</x-app-layout>
