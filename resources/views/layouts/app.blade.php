<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ $title ?? config('app.name', 'CMS') }}</title>

  <style>
    [x-cloak] {
      display: none !important;
    }
  </style>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">
  <header class="border-b border-slate-200 bg-white">
    <nav class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
      <a href="{{ route('home') }}" class="text-sm font-semibold text-slate-950">
        CMS
      </a>

      <div class="flex items-center gap-4 text-sm">
        @guest
          <a href="{{ route('login') }}" class="text-slate-600 hover:text-slate-950">Login</a>
          <a href="{{ route('register') }}"
            class="rounded-md bg-slate-950 px-3 py-2 font-medium text-white hover:bg-slate-800">Register</a>
        @else
          <a href="{{ route('dashboard') }}" class="text-slate-600 hover:text-slate-950">Dashboard</a>
          <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">
            <button type="button"
              class="flex items-center gap-2 rounded-md border border-slate-300 px-3 py-2 font-medium text-slate-700 hover:bg-slate-100"
              @click="open = ! open" @click.outside="open = false">
              <span>{{ auth()->user()->name }}</span>
              <span class="text-xs text-slate-400">v</span>
            </button>

            <div x-cloak x-show="open" x-transition
              class="absolute right-0 z-20 mt-2 w-44 rounded-md border border-slate-200 bg-white py-1 shadow-lg">
              <a href="{{ route('settings.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                Settings
              </a>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-50">
                  Logout
                </button>
              </form>
            </div>
          </div>
        @endguest
      </div>
    </nav>
  </header>

  @if (session('status'))
    <div class="mx-auto mt-6 max-w-6xl px-6">
      <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('status') }}
      </div>
    </div>
  @endif

  <main>
    {{ $slot }}
  </main>
</body>

</html>
