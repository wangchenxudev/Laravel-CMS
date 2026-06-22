@props(['title' => null])

<x-layouts.base :title="$title" body-class="flex min-h-full flex-col">
  {{-- Header --}}
  <header class="border-b border-slate-200 bg-white">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
      <div class="flex items-center gap-8">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-base font-bold tracking-tight text-slate-900">
          <svg class="h-6 w-6 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4h-2m2 4h-2m2 4h-2M9 8h4m-4 4h4m-4 4h2" />
          </svg>
          <span>{{ config('app.name', 'Enterprise CMS') }}</span>
        </a>

        <nav class="hidden items-center gap-6 md:flex">
          <x-ui.nav-link variant="top" :href="route('home')" :active="request()->routeIs('home')">Home</x-ui.nav-link>
          <x-ui.nav-link variant="top" :href="route('published.articles.index')" :active="request()->routeIs('published.articles.*')">Articles</x-ui.nav-link>
        </nav>
      </div>

      <div class="flex items-center gap-4">
        @guest
          <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 transition-colors hover:text-slate-900">Login</a>
          <a href="{{ route('register') }}"
            class="rounded bg-brand-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-brand-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-500 active:scale-95">
            Register
          </a>
        @else
          <a href="{{ route('dashboard') }}" class="text-sm font-medium text-slate-600 transition-colors hover:text-slate-900">Dashboard</a>
          @if (auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-slate-600 transition-colors hover:text-slate-900">Admin Console</a>
          @endif
          <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="cursor-pointer text-sm font-medium text-slate-600 transition-colors hover:text-slate-900">Logout</button>
          </form>
        @endguest
      </div>
    </div>
  </header>

  {{-- Main Content --}}
  <main class="flex-1">
    @if (session('status'))
      <div class="mx-auto mt-6 max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-ui.alert type="success">{{ session('status') }}</x-ui.alert>
      </div>
    @endif

    {{ $slot }}
  </main>

  {{-- Footer --}}
  <footer class="border-t border-slate-200 bg-white py-6">
    <div class="mx-auto max-w-7xl px-4 text-center text-xs text-slate-500 sm:px-6 lg:px-8">
      &copy; {{ date('Y') }} {{ config('app.name', 'Enterprise CMS') }}. All rights reserved.
    </div>
  </footer>
</x-layouts.base>
