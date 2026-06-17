<x-app-layout title="{{ $article->title }}">
  <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-10 md:py-16">
    <div class="mb-8">
      <a href="{{ route('published.articles.index') }}" class="inline-flex items-center text-sm font-semibold text-[#1890FF] hover:text-[#40a9ff] transition-colors mb-6">
        ← Back to Articles
      </a>
      
      <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 leading-tight">
        {{ $article->title }}
      </h1>

      <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-slate-500 font-medium border-y border-slate-200 py-3">
        <div class="flex items-center gap-1.5">
          <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          <span class="text-slate-700 font-semibold">{{ $article->author->name }}</span>
        </div>
        <span>•</span>
        <div class="flex items-center gap-1.5">
          <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <span>Published on {{ $article->approved_at ? $article->approved_at->format('Y-m-d') : $article->created_at->format('Y-m-d') }}</span>
        </div>
      </div>

      @if ($article->summary)
        <div class="mt-6 rounded-r-md border-l-4 border-[#1890FF] bg-slate-100 p-4">
          <p class="text-sm font-semibold text-slate-700">Summary:</p>
          <p class="mt-1 text-sm leading-relaxed text-slate-600 italic">{{ $article->summary }}</p>
        </div>
      @endif
    </div>

    <!-- Article Content -->
    <article class="prose max-w-none text-slate-800 leading-relaxed whitespace-pre-line text-base font-normal tracking-wide py-4 border-b border-slate-200">
      {{ $article->content }}
    </article>

    <div class="mt-8 text-center">
      <a href="{{ route('published.articles.index') }}" 
        class="inline-flex items-center justify-center rounded border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 shadow-sm active:scale-95 transition-all">
        Back to Articles
      </a>
    </div>
  </div>
</x-app-layout>
