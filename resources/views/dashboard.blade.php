<x-app-layout title="Workspace">
@php
  $user = auth()->user();
@endphp

  <div class="space-y-6">
    <section class="overflow-hidden rounded border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-200 bg-slate-50/60 px-6 py-5">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
          <div>
            <div class="inline-flex items-center gap-2 rounded border border-[#1890FF]/20 bg-[#1890FF]/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-[#1890FF]">
              <span class="h-1.5 w-1.5 rounded-full bg-[#1890FF]"></span>
              Author Workspace
            </div>
            <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-900">Welcome back, {{ $user->name }}</h1>
            <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-500">
              Manage your own submissions, continue drafts, and send finished articles into the editorial review queue.
            </p>
          </div>

          <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('published.articles.index') }}"
              class="inline-flex items-center justify-center rounded border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 hover:text-slate-900">
              Browse Articles
            </a>
            <a href="{{ route('articles.create') }}"
              class="inline-flex items-center justify-center rounded bg-[#1890FF] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#40a9ff] active:scale-95">
              New Article
            </a>
          </div>
        </div>
      </div>

      <div class="grid divide-y divide-slate-100 md:grid-cols-3 md:divide-x md:divide-y-0">
        <div class="px-6 py-5">
          <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Role</p>
          <p class="mt-2 text-sm font-semibold text-slate-900">{{ $user->role->value === 'admin' ? 'Admin' : 'User' }}</p>
          <span class="hidden">Current role {{ $user->role->value }}</span>
        </div>

        <div class="px-6 py-5">
          <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Publishing Flow</p>
          <p class="mt-2 text-sm font-semibold text-slate-900">Draft -> Review -> Published</p>
        </div>

        <div class="px-6 py-5">
          <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Account Status</p>
          <div class="mt-2 flex items-center gap-2">
            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
            <span class="text-sm font-semibold text-slate-900">Active</span>
          </div>
        </div>
      </div>
    </section>

    <div class="grid gap-6 lg:grid-cols-[1.3fr_0.7fr]">
      <section class="rounded border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-4">
          <h2 class="text-base font-semibold text-slate-900">Editorial Tasks</h2>
          <p class="mt-1 text-sm text-slate-500">Common content actions for registered authors.</p>
        </div>

        <div class="divide-y divide-slate-100">
          <a href="{{ route('articles.index') }}" class="flex items-center justify-between gap-4 px-6 py-4 transition hover:bg-slate-50">
            <div>
              <p class="text-sm font-semibold text-slate-900">My Articles</p>
              <p class="mt-1 text-sm text-slate-500">Review drafts, rejected submissions, and published records you own.</p>
            </div>
            <svg class="h-5 w-5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
          </a>

          <a href="{{ route('articles.create') }}" class="flex items-center justify-between gap-4 px-6 py-4 transition hover:bg-slate-50">
            <div>
              <p class="text-sm font-semibold text-slate-900">Create Draft</p>
              <p class="mt-1 text-sm text-slate-500">Start a new article and submit it when it is ready for review.</p>
            </div>
            <svg class="h-5 w-5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
          </a>

          <a href="{{ route('published.articles.index') }}" class="flex items-center justify-between gap-4 px-6 py-4 transition hover:bg-slate-50">
            <div>
              <p class="text-sm font-semibold text-slate-900">Public Content Library</p>
              <p class="mt-1 text-sm text-slate-500">Browse approved articles and use title search to find published content.</p>
            </div>
            <svg class="h-5 w-5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
          </a>
        </div>
      </section>

      <aside class="rounded border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-base font-semibold text-slate-900">Submission Rules</h2>
        <div class="mt-4 space-y-4 text-sm leading-6 text-slate-600">
          <p>Drafts remain private until you submit them for administrator review.</p>
          <p>Rejected articles can be revised and submitted again from your article list.</p>
          <p>Published articles are visible in the public content library after approval.</p>
        </div>
      </aside>
    </div>
  </div>
</x-app-layout>
