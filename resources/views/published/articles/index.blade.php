<x-app-layout title="Articles">
  <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="border-b border-slate-200 pb-6">
      <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
        <div>
          <div class="inline-flex items-center gap-2 rounded border border-[#1890FF]/20 bg-[#1890FF]/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-[#1890FF]">
            <span class="h-1.5 w-1.5 rounded-full bg-[#1890FF]"></span>
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

    <section class="mt-6 rounded border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-200 p-4 sm:p-5">
        <form method="GET" action="{{ route('published.articles.index') }}" class="flex flex-col gap-3 md:flex-row md:items-center">
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
              class="block w-full rounded border border-slate-300 bg-white py-2.5 pl-9 pr-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-[#1890FF] focus:ring-2 focus:ring-[#1890FF]/20"
            >
          </div>

          <div class="flex items-center gap-2">
            @if ($search !== '')
              <a href="{{ route('published.articles.index') }}" class="inline-flex justify-center rounded border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                Clear
              </a>
            @endif
            <button type="submit" class="inline-flex justify-center rounded bg-[#1890FF] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#40a9ff] active:scale-95">
              Search
            </button>
          </div>
        </form>

        @if ($search !== '')
          <p class="mt-3 text-sm text-slate-500">
            Showing title matches for <span class="font-semibold text-slate-700">"{{ $search }}"</span>.
          </p>
        @endif
      </div>

      <div class="divide-y divide-slate-100">
        @forelse ($articles as $article)
          <article class="group px-4 py-5 transition hover:bg-slate-50 sm:px-5">
            <div class="grid gap-4 lg:grid-cols-[1fr_13rem_8rem] lg:items-start">
              <div class="min-w-0">
                <a href="{{ route('published.articles.show', $article->publicRouteParameters()) }}" class="text-base font-semibold text-slate-900 transition group-hover:text-[#1890FF]">
                  {{ $article->title }}
                </a>
                @if ($article->summary)
                  <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-600">{{ $article->summary }}</p>
                @endif
              </div>

              <div class="flex items-center gap-2 text-sm text-slate-500 lg:justify-start">
                <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a8.25 8.25 0 0 1 15 0" />
                </svg>
                <span class="truncate">{{ $article->author->name }}</span>
              </div>

              <div class="text-sm text-slate-500 lg:text-right">
                {{ $article->approved_at ? $article->approved_at->format('Y-m-d') : $article->created_at->format('Y-m-d') }}
              </div>
            </div>
          </article>
        @empty
          <div class="px-6 py-14 text-center">
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
        @endforelse
      </div>
    </section>

    <div class="mt-6">
      {{ $articles->links() }}
    </div>
  </div>
</x-app-layout>
