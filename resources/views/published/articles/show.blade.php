<x-app-layout title="{{ $article->title }}">
  <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    <a href="{{ route('published.articles.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-500 transition hover:text-brand-600">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
      </svg>
      Back to Articles
    </a>

    <article class="mt-6 overflow-hidden rounded border border-slate-200 bg-white shadow-sm">
      <header class="border-b border-slate-200 px-5 py-6 sm:px-8 sm:py-8">
        <div class="inline-flex items-center gap-2 rounded border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-slate-500">
          Published Article
        </div>

        <h1 class="mt-4 text-3xl font-bold leading-tight tracking-tight text-slate-900 sm:text-4xl">
          {{ $article->title }}
        </h1>

        <div class="mt-5 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-slate-500">
          <div class="flex items-center gap-2">
            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a8.25 8.25 0 0 1 15 0" />
            </svg>
            <span class="font-semibold text-slate-700">{{ $article->author->name }}</span>
          </div>

          <div class="flex items-center gap-2">
            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M4.5 6.75A2.25 2.25 0 0 1 6.75 4.5h10.5a2.25 2.25 0 0 1 2.25 2.25v10.5a2.25 2.25 0 0 1-2.25 2.25H6.75a2.25 2.25 0 0 1-2.25-2.25V6.75Z" />
            </svg>
            <span>Published {{ $article->approved_at ? $article->approved_at->format('Y-m-d') : $article->created_at->format('Y-m-d') }}</span>
          </div>
        </div>

        @if ($article->tags->isNotEmpty())
          <div class="mt-4 flex flex-wrap gap-2">
            @foreach ($article->tags as $tag)
              <x-ui.tag-badge :href="route('published.articles.index', ['tag' => $tag->slug])">{{ $tag->name }}</x-ui.tag-badge>
            @endforeach
          </div>
        @endif

        @if ($article->summary)
          <div class="mt-6 border-l-4 border-brand-500 bg-slate-50 px-4 py-3">
            <p class="text-sm font-semibold text-slate-700">Summary</p>
            <p class="mt-1 text-sm leading-6 text-slate-600">{{ $article->summary }}</p>
          </div>
        @endif
      </header>

      <div class="px-5 py-6 sm:px-8 sm:py-8">
        <div class="prose max-w-none whitespace-pre-line text-base leading-8 text-slate-800">
          {{ $article->content }}
        </div>

        @if ($article->images->isNotEmpty())
          <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
            @foreach ($article->images as $image)
              <figure class="overflow-hidden rounded border border-slate-200 bg-slate-50">
                <img src="{{ $image->url }}" alt="{{ $image->original_name ?? $article->title }}" class="w-full object-cover" loading="lazy" />
              </figure>
            @endforeach
          </div>
        @endif
      </div>
    </article>

    @if (isset($moreArticles) && $moreArticles->isNotEmpty())
      <section class="mt-12">
        <h2 class="text-lg font-bold tracking-tight text-slate-900">More Articles</h2>
        <div class="mt-5 grid grid-cols-1 gap-x-5 gap-y-8 sm:grid-cols-2 lg:grid-cols-4">
          @foreach ($moreArticles as $more)
            <x-ui.article-card :article="$more" />
          @endforeach
        </div>
      </section>
    @endif
  </div>
</x-app-layout>
