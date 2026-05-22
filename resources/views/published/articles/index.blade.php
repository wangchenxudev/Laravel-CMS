<x-app-layout title="Published Articles">
  <div class="mx-auto max-w-6xl px-6 py-10">
    <div class="border-b border-zinc-200 pb-6">
      <h1 class="text-3xl font-semibold tracking-tight text-zinc-950">Published Articles</h1>
      <p class="mt-2 text-sm text-zinc-600">Public articles approved by the administrator.</p>
    </div>

    <div class="mt-6 grid gap-4">
      @forelse ($articles as $article)
        <a href="{{ route('published.articles.show', $article) }}" class="rounded-sm border border-zinc-200 bg-white p-5 hover:bg-zinc-50">
          <h2 class="font-semibold text-zinc-950">{{ $article->title }}</h2>
          <p class="mt-1 text-sm text-zinc-600">By {{ $article->author->name }}</p>
          @if ($article->summary)
            <p class="mt-3 text-sm leading-6 text-zinc-700">{{ $article->summary }}</p>
          @endif
        </a>
      @empty
        <div class="rounded-sm border border-zinc-200 bg-white px-5 py-10 text-sm text-zinc-600">No published articles.</div>
      @endforelse
    </div>

    <div class="mt-6">
      {{ $articles->links() }}
    </div>
  </div>
</x-app-layout>
