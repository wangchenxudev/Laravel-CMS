<x-app-layout title="Article Reviews">
  <div class="mx-auto max-w-6xl px-6 py-10">
    <div class="border-b border-zinc-200 pb-6">
      <h1 class="text-3xl font-semibold tracking-tight text-zinc-950">Article Reviews</h1>
      <p class="mt-2 text-sm text-zinc-600">Pending articles waiting for admin review.</p>
    </div>

    <div class="mt-6 overflow-hidden rounded-sm border border-zinc-200 bg-white">
      @forelse ($articles as $article)
        <a href="{{ route('admin.articles.show', $article) }}" class="block border-b border-zinc-100 px-5 py-4 last:border-b-0 hover:bg-zinc-50">
          <div class="flex items-center justify-between gap-4">
            <div>
              <h2 class="font-semibold text-zinc-950">{{ $article->title }}</h2>
              <p class="mt-1 text-sm text-zinc-600">By {{ $article->author->name }}</p>
            </div>
            <span class="text-xs font-medium text-zinc-500">{{ $article->created_at->diffForHumans() }}</span>
          </div>
        </a>
      @empty
        <div class="px-5 py-10 text-sm text-zinc-600">No pending articles.</div>
      @endforelse
    </div>

    <div class="mt-6">
      {{ $articles->links() }}
    </div>
  </div>
</x-app-layout>
