<x-app-layout title="Articles">
  <div class="mx-auto max-w-7xl">
    <div class="border-b border-slate-200 pb-6">
      <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
        <div>
          <div class="inline-flex items-center gap-2 rounded border border-brand-500/20 bg-brand-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-brand-500">
            <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>
            Content Library
          </div>
          <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-900">Articles</h1>
          <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
            Browse approved articles from the CMS publishing workflow.
          </p>
        </div>

        <div class="rounded border border-slate-200 bg-white px-4 py-3 shadow-sm">
          <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Published Records</p>
          <p class="mt-1 text-2xl font-bold text-slate-900">{{ $articles->total() }}</p>
        </div>
      </div>
    </div>

    <div class="mt-6">
      <form method="GET" action="{{ route('published.articles.index') }}" class="flex flex-col gap-3 md:flex-row md:items-center">
        @if ($activeTag)
          <input type="hidden" name="tag" value="{{ $activeTag->slug }}">
        @endif
        <div class="relative flex-1">
          <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.04 6.04a7.5 7.5 0 0 0 10.61 10.61Z" />
            </svg>
          </div>
          <input
            type="search"
            name="q"
            value="{{ $search }}"
            placeholder="Search article titles"
            class="block w-full rounded border border-slate-300 bg-white py-2.5 pl-9 pr-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20"
          >
        </div>

        <div class="flex items-center gap-2">
          @if ($search !== '')
            <a href="{{ route('published.articles.index', array_filter(['tag' => $activeTag?->slug])) }}" class="inline-flex justify-center rounded border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
              Clear
            </a>
          @endif
          <button type="submit" class="inline-flex justify-center rounded bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-600 active:scale-95">
            Search
          </button>
        </div>
      </form>

      @if ($filterTags->isNotEmpty())
        <div class="mt-4 flex flex-wrap items-center gap-2">
          <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tags</span>
          <a href="{{ route('published.articles.index', array_filter(['q' => $search ?: null])) }}"
            @class([
              'inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold transition',
              'border-brand-500 bg-brand-50 text-brand-700' => ! $activeTag,
              'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' => $activeTag,
            ])>
            All
          </a>
          @foreach ($filterTags as $tag)
            <a href="{{ route('published.articles.index', array_filter(['tag' => $tag->slug, 'q' => $search ?: null])) }}"
              @class([
                'inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold transition',
                'border-brand-500 bg-brand-50 text-brand-700' => $activeTag?->id === $tag->id,
                'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' => $activeTag?->id !== $tag->id,
              ])>
              {{ $tag->name }}
            </a>
          @endforeach
        </div>
      @endif

      @if ($search !== '' || $activeTag)
        <p class="mt-3 text-sm text-slate-500">
          Showing
          @if ($search !== '')
            title matches for <span class="font-semibold text-slate-700">"{{ $search }}"</span>
          @endif
          @if ($activeTag)
            {{ $search !== '' ? 'tagged' : 'articles tagged' }} <span class="font-semibold text-slate-700">{{ $activeTag->name }}</span>
          @endif.
        </p>
      @endif
    </div>

    @if ($articles->count() > 0)
      <div class="mt-8 grid grid-cols-1 gap-x-5 gap-y-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @foreach ($articles as $article)
          <x-ui.article-card :article="$article" />
        @endforeach
      </div>
    @else
      <div class="mt-8 rounded border border-slate-200 bg-white px-6 py-16 text-center shadow-sm">
        <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5A3.375 3.375 0 0 0 10.125 2.25H8.25M8.25 15h7.5M8.25 18H12m-1.5-15.75H5.625A1.125 1.125 0 0 0 4.5 3.375v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
        </svg>
        <h3 class="mt-4 text-sm font-semibold text-slate-900">
          {{ $search !== '' ? 'No matching articles found' : 'No published articles yet' }}
        </h3>
        <p class="mt-1 text-sm text-slate-500">
          {{ $search !== '' ? 'Try a different title keyword.' : 'Please check back later.' }}
        </p>
      </div>
    @endif

    <div class="mt-8">
      {{ $articles->links() }}
    </div>
  </div>
</x-app-layout>
