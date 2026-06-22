@props(['title' => null])

@php
    $user = auth()->user();
    $isAdmin = $user?->isAdmin() ?? false;

    $isBrowse = request()->routeIs('published.articles.*');
    $isMyArticles = request()->routeIs('articles.index') || request()->routeIs('articles.show') || request()->routeIs('articles.edit');
    $isNewArticle = request()->routeIs('articles.create');
    $isReviews = request()->routeIs('admin.articles.reviews.index') || request()->routeIs('admin.articles.show');
    $isAdminDashboard = request()->routeIs('admin.dashboard');
    $isTags = request()->routeIs('admin.tags.*');
@endphp

<x-layouts.base :title="$title" body-class="h-full"
  x-data="{
    sidebarOpen: false,
    collapsed: false,
    init() {
      this.collapsed = localStorage.getItem('cms_sidebar_collapsed') === '1';
      this.$watch('collapsed', value => localStorage.setItem('cms_sidebar_collapsed', value ? '1' : '0'));
    },
    toggle() {
      if (window.matchMedia('(min-width: 1024px)').matches) {
        this.collapsed = !this.collapsed;
      } else {
        this.sidebarOpen = !this.sidebarOpen;
      }
    },
  }">
  {{-- Mobile Sidebar Overlay --}}
  <div x-cloak x-show="sidebarOpen" class="fixed inset-0 z-40 bg-slate-900/80 lg:hidden" @click="sidebarOpen = false" x-transition.opacity></div>

  {{-- Left Sidebar Navigation --}}
  <aside
    :class="{
      'translate-x-0': sidebarOpen,
      '-translate-x-full': !sidebarOpen,
      'lg:translate-x-0': !collapsed,
      'lg:-translate-x-full': collapsed,
    }"
    class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-slate-800 bg-slate-900 text-slate-300 transition-transform duration-200 ease-in-out">

    {{-- Sidebar Header / Logo --}}
    <div class="flex h-16 shrink-0 items-center border-b border-slate-800 bg-slate-950 px-6">
      <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold tracking-tight text-white">
        <svg class="h-6 w-6 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4h-2m2 4h-2m2 4h-2M9 8h4m-4 4h4m-4 4h2" />
        </svg>
        <span>{{ config('app.name', 'Enterprise CMS') }}</span>
      </a>
    </div>

    {{-- Sidebar Navigation Menu --}}
    <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6">
      <p class="px-3 pb-1 text-xs font-semibold uppercase tracking-wider text-slate-500">Content</p>
      <div class="space-y-1">
        <x-ui.nav-link :href="route('published.articles.index')" :active="$isBrowse">
          <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4h-2m2 4h-2m2 4h-2M9 8h4m-4 4h4m-4 4h2" />
          </svg>
          Browse Articles
        </x-ui.nav-link>

        <x-ui.nav-link :href="route('articles.index')" :active="$isMyArticles">
          <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
          </svg>
          My Articles
        </x-ui.nav-link>

        <x-ui.nav-link :href="route('articles.create')" :active="$isNewArticle">
          <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
          </svg>
          New Article
        </x-ui.nav-link>
      </div>

      @if ($isAdmin)
        <div class="pt-6">
          <p class="px-3 pb-1 text-xs font-semibold uppercase tracking-wider text-slate-500">Administration</p>
          <div class="space-y-1">
            <x-ui.nav-link :href="route('admin.articles.reviews.index')" :active="$isReviews">
              <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Article Reviews
            </x-ui.nav-link>

            <x-ui.nav-link :href="route('admin.tags.index')" :active="$isTags">
              <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A2 2 0 014 12V7a4 4 0 014-4z" />
              </svg>
              Manage Tags
            </x-ui.nav-link>

            <x-ui.nav-link :href="route('admin.dashboard')" :active="$isAdminDashboard">
              <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
              Admin Console
            </x-ui.nav-link>
          </div>
        </div>
      @endif
    </nav>
  </aside>

  {{-- Right / Main Area Wrapper --}}
  <div class="flex min-h-screen flex-col transition-[padding] duration-200 ease-in-out" :class="collapsed ? 'lg:pl-0' : 'lg:pl-64'">

    {{-- Top Global Header --}}
    <header class="flex h-16 shrink-0 items-center justify-between border-b border-slate-200 bg-white px-6 shadow-sm">
      <div class="flex items-center gap-4">
        <button type="button" @click="toggle()"
          class="cursor-pointer rounded p-1.5 text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700"
          :aria-expanded="window.matchMedia('(min-width: 1024px)').matches ? !collapsed : sidebarOpen"
          aria-label="Toggle sidebar">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>

        <span class="text-base font-semibold tracking-tight text-slate-800">
          {{ $title ?? 'Console' }}
        </span>
      </div>

      {{-- User Actions Dropdown --}}
      <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">
        <button type="button" @click="open = !open" @click.outside="open = false"
          class="flex cursor-pointer items-center gap-2 rounded border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition-all hover:bg-slate-50 active:scale-95">
          <span class="flex h-6 w-6 items-center justify-center rounded-full bg-brand-500 text-xs font-semibold text-white">
            {{ strtoupper(mb_substr($user?->name ?? '?', 0, 1)) }}
          </span>
          <span>{{ $user?->name }}</span>
          <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
          </svg>
        </button>

        <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-100"
          x-transition:enter-start="transform opacity-0 scale-95"
          x-transition:enter-end="transform opacity-100 scale-100"
          x-transition:leave="transition ease-in duration-75"
          x-transition:leave-start="transform opacity-100 scale-100"
          x-transition:leave-end="transform opacity-0 scale-95"
          class="absolute right-0 z-50 mt-2 w-56 origin-top-right rounded-md border border-slate-200 bg-white py-1 shadow-lg">

          <div class="border-b border-slate-100 px-4 py-3">
            <p class="truncate text-sm font-semibold text-slate-900">{{ $user?->name }}</p>
            <p class="mt-0.5 text-xs font-medium text-brand-500">{{ $isAdmin ? 'Administrator' : 'Editor' }}</p>
          </div>

          <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Dashboard</a>

          <a href="{{ route('settings.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Settings</a>

          <div class="my-1 border-t border-slate-100"></div>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full cursor-pointer px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-50 transition-colors">
              Logout
            </button>
          </form>
        </div>
      </div>
    </header>

    {{-- Main Content Area --}}
    <main class="flex-1 p-6 md:p-8">
      @if (session('status'))
        <x-ui.alert type="success" class="mb-6">{{ session('status') }}</x-ui.alert>
      @endif

      {{ $slot }}
    </main>

    {{-- Console Footer --}}
    <footer class="border-t border-slate-200 bg-white px-6 py-4 text-center text-xs text-slate-500">
      &copy; {{ date('Y') }} {{ config('app.name', 'Enterprise CMS') }}. All rights reserved.
    </footer>
  </div>
</x-layouts.base>
