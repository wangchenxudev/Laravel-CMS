<x-app-layout title="Published Articles">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 md:py-16">
    <div class="border-b border-slate-200 pb-6">
      <h1 class="text-3xl font-bold tracking-tight text-slate-900">Published Articles</h1>
      <p class="mt-2 text-sm text-slate-500">Public articles approved by the administrator.</p>
    </div>

    <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      @forelse ($articles as $article)
        <a href="{{ route('published.articles.show', $article->publicRouteParameters()) }}" 
          class="flex flex-col justify-between overflow-hidden rounded border border-slate-200 bg-white p-6 shadow-sm hover:border-[#1890FF] hover:shadow transition-all group">
          <div>
            <h2 class="text-lg font-bold text-slate-900 group-hover:text-[#1890FF] transition-colors line-clamp-2">
              {{ $article->title }}
            </h2>
            <div class="mt-2 flex items-center gap-2 text-xs text-slate-500 font-medium">
              <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded">{{ $article->author->name }}</span>
              <span>•</span>
              <span>{{ $article->approved_at ? $article->approved_at->format('Y-m-d') : $article->created_at->format('Y-m-d') }}</span>
            </div>
            @if ($article->summary)
              <p class="mt-4 text-sm leading-relaxed text-slate-600 line-clamp-3">{{ $article->summary }}</p>
            @endif
          </div>
          
          <div class="mt-6 pt-4 border-t border-slate-100 flex items-center text-xs font-semibold text-[#1890FF]">
            Read Full Article <span class="ml-1 transition-transform group-hover:translate-x-1">→</span>
          </div>
        </a>
      @empty
        <div class="sm:col-span-2 lg:col-span-3 rounded border border-slate-200 bg-white px-6 py-12 text-center">
          <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
          </svg>
          <h3 class="mt-4 text-sm font-semibold text-slate-900">No published articles yet</h3>
          <p class="mt-1 text-sm text-slate-500">Please check back later.</p>
        </div>
      @endforelse
    </div>

    <div class="mt-8">
      {{ $articles->links() }}
    </div>
  </div>
</x-app-layout>
