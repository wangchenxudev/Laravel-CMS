<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ $title ?? config('app.name', 'CMS') }} - Console</title>

  <!-- Google Fonts: Instrument Sans -->
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

<body class="h-full font-sans text-slate-900 antialiased" x-data="{ sidebarOpen: false }">
  <!-- Mobile Sidebar Overlay -->
  <div x-cloak x-show="sidebarOpen" class="fixed inset-0 z-50 bg-slate-900/80 lg:hidden" @click="sidebarOpen = false" x-transition.opacity></div>

  <!-- Left Sidebar Navigation -->
  <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
    class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-slate-800 bg-slate-900 text-slate-300 transition-transform duration-200 ease-in-out lg:translate-x-0">
    
    <!-- Sidebar Header / Logo -->
    <div class="flex h-16 shrink-0 items-center border-b border-slate-800 px-6 bg-slate-950">
      <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold tracking-tight text-white">
        <svg class="h-6 w-6 text-[#1890FF]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4h-2m2 4h-2m2 4h-2M9 8h4m-4 4h4m-4 4h2" />
        </svg>
        <span>{{ config('app.name', 'Enterprise CMS') }}</span>
      </a>
    </div>

    <!-- Sidebar Navigation Menu -->
    <nav class="flex-1 space-y-1 px-4 py-6 overflow-y-auto">
      <div class="space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" 
          class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded transition-colors {{ request()->routeIs('dashboard') ? 'bg-[#1890FF] text-white' : 'hover:bg-slate-800 hover:text-white' }}">
          <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
          </svg>
          Dashboard
        </a>

        <!-- Articles -->
        <a href="{{ route('articles.index') }}" 
          class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded transition-colors {{ request()->routeIs('articles.*') && !request()->routeIs('admin.*') ? 'bg-[#1890FF] text-white' : 'hover:bg-slate-800 hover:text-white' }}">
          <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4h-2m2 4h-2m2 4h-2M9 8h4m-4 4h4m-4 4h2" />
          </svg>
          Articles
        </a>

        <!-- Settings -->
        <a href="{{ route('settings.edit') }}" 
          class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded transition-colors {{ request()->routeIs('settings.*') ? 'bg-[#1890FF] text-white' : 'hover:bg-slate-800 hover:text-white' }}">
          <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          Settings
        </a>
      </div>

      <!-- Admin Group -->
      @if (auth()->user()->isAdmin())
        <div class="pt-6">
          <p class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Administration</p>
          <div class="mt-2 space-y-1">
            <a href="{{ route('admin.dashboard') }}" 
              class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#1890FF] text-white' : 'hover:bg-slate-800 hover:text-white' }}">
              <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
              Admin Dashboard
            </a>
            <a href="{{ route('admin.articles.reviews.index') }}" 
              class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded transition-colors {{ request()->routeIs('admin.articles.reviews.index') || request()->routeIs('admin.articles.show') ? 'bg-[#1890FF] text-white' : 'hover:bg-slate-800 hover:text-white' }}">
              <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Article Reviews
            </a>
          </div>
        </div>
      @endif
    </nav>
  </aside>

  <!-- Right / Main Area Wrapper -->
  <div class="flex flex-col min-h-screen lg:pl-64">
    
    <!-- Top Global Header -->
    <header class="flex h-16 shrink-0 items-center justify-between border-b border-slate-200 bg-white px-6 shadow-sm">
      <div class="flex items-center gap-4">
        <!-- Sidebar Toggle (Mobile) -->
        <button type="button" @click="sidebarOpen = true" class="text-slate-500 hover:text-slate-700 lg:hidden cursor-pointer">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>

        <!-- Current Section / Title -->
        <span class="text-base font-semibold text-slate-800 tracking-tight">
          {{ $title ?? 'Console' }}
        </span>
      </div>

      <!-- User Actions Dropdown -->
      <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">
        <button type="button" @click="open = !open" @click.outside="open = false"
          class="flex items-center gap-2 rounded px-3 py-2 text-sm font-medium text-slate-700 border border-slate-200 hover:bg-slate-50 active:scale-95 transition-all cursor-pointer">
          <span>{{ auth()->user()->name }}</span>
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
          class="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-md border border-slate-200 bg-white py-1 shadow-lg">
          
          <a href="{{ route('settings.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Settings</a>
          
          <div class="border-t border-slate-100 my-1"></div>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer">
              Logout
            </button>
          </form>
        </div>
      </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 p-6 md:p-8">
      <!-- Session Flash Messages -->
      @if (session('status'))
        <div class="mb-6 rounded-md border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 flex items-start gap-3 shadow-sm" x-data="{ show: true }" x-show="show">
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
      @endif

      {{ $slot }}
    </main>

    <!-- Console Footer -->
    <footer class="border-t border-slate-200 bg-white py-4 px-6 text-center text-xs text-slate-500">
      &copy; {{ date('Y') }} {{ config('app.name', 'Enterprise CMS') }}. All rights reserved.
    </footer>
  </div>
</body>

</html>
