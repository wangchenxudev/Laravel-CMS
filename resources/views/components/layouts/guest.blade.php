@props(['title' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ $title ?? config('app.name', 'CMS') }}</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

  <style>
    [x-cloak] {
      display: none !important;
    }
  </style>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-full flex-col bg-slate-50 font-sans text-slate-900 antialiased">
  <!-- Header -->
  <header class="border-b border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8 py-4 flex">
      <div class="flex items-center gap-8">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-base font-bold tracking-tight text-slate-900">
          <svg class="h-6 w-6 text-[#1890FF]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4h-2m2 4h-2m2 4h-2M9 8h4m-4 4h4m-4 4h2" />
          </svg>
          <span>{{ config('app.name', 'Enterprise CMS') }}</span>
        </a>

        <nav class="hidden md:flex items-center gap-6">
          <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#1890FF]' : 'text-slate-600 hover:text-slate-900' }} text-sm font-medium transition-colors">Home</a>
          <a href="{{ route('published.articles.index') }}" class="{{ request()->routeIs('published.articles.*') ? 'text-[#1890FF]' : 'text-slate-600 hover:text-slate-900' }} text-sm font-medium transition-colors">Articles</a>
        </nav>
      </div>

      <div class="flex items-center gap-4">
        @guest
          <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">Login</a>
          <a href="{{ route('register') }}"
            class="rounded bg-[#1890FF] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#40a9ff] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#1890FF] active:scale-95 transition-all">
            Register
          </a>
        @else
          <a href="{{ route('dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">Dashboard</a>
          @if (auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">Admin Console</a>
          @endif
          <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors cursor-pointer">Logout</button>
          </form>
        @endguest
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="flex-1">
    @if (session('status'))
      <div class="mx-auto mt-6 max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-md border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 flex items-start gap-3 shadow-sm" x-data="{ show: true }" x-show="show">
          <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div class="flex-1 font-medium">{{ session('status') }}</div>
          <button type="button" @click="show = false" class="text-emerald-500 hover:text-emerald-700 shrink-0 cursor-pointer">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    @endif

    {{ $slot }}
  </main>

  <!-- Footer -->
  <footer class="border-t border-slate-200 bg-white py-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-500">
      &copy; {{ date('Y') }} {{ config('app.name', 'Enterprise CMS') }}. All rights reserved.
    </div>
  </footer>
</body>

</html>
