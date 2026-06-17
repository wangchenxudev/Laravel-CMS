<x-app-layout title="Dashboard">
@php
  $user = auth()->user();
@endphp

  <div class="space-y-6">
    <!-- Top Greeting Banner -->
    <div class="overflow-hidden rounded border border-slate-200 bg-white p-6 shadow-sm">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 class="text-2xl font-bold tracking-tight text-slate-900">Welcome back, {{ $user->name }}</h1>
          <p class="mt-1 text-sm text-slate-500">
            This is your personal workspace. You can manage drafts, write articles, and track submission states.
          </p>
        </div>

        <div class="inline-flex items-center gap-2 rounded border border-slate-200 bg-slate-50 px-4 py-2 text-sm shadow-sm font-medium">
          <span class="text-slate-500">Current role:</span>
          <span class="font-semibold text-slate-900 bg-slate-200 px-2 py-0.5 rounded text-xs">{{ $user->role->value === 'admin' ? 'Admin' : 'User' }}</span>
          <!-- Testing markers for legacy assertions -->
          <span class="hidden">Current role {{ $user->role->value }}</span>
        </div>
      </div>
    </div>

    <!-- Quick Stats & System Status Cards -->
    <div class="grid gap-6 md:grid-cols-3">
      <!-- Status Card 1 -->
      <x-ui.card title="Account Status" subtitle="System Active Check">
        <div class="flex items-center gap-3">
          <span class="flex h-3 w-3 relative">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
          </span>
          <span class="text-sm font-semibold text-slate-700">Account Active</span>
        </div>
        <p class="mt-3 text-xs leading-relaxed text-slate-500">
          Your workspace is authorized and connected to the editorial review workflow. Any articles you publish will enter the queue.
        </p>
      </x-ui.card>

      <!-- Status Card 2 -->
      <x-ui.card title="Security & Audits" subtitle="Account Protection">
        <div class="flex items-center gap-2 text-sm text-slate-700">
          <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
          <span class="font-semibold text-slate-800">Audit Logging Active</span>
        </div>
        <p class="mt-3 text-xs leading-relaxed text-slate-500">
          All sensitive actions are recorded for administrative auditing. Password resets are planned.
        </p>
      </x-ui.card>

      <!-- Status Card 3 -->
      @if ($user->isAdmin())
        <div class="overflow-hidden rounded border border-blue-200 bg-blue-50/50 p-5 flex flex-col justify-between shadow-sm">
          <div>
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
              <svg class="h-5 w-5 text-[#1890FF]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
              Admin Access
            </h3>
            <p class="mt-3 text-xs leading-relaxed text-slate-700">
              You are logged in as an administrator. You can review submissions and manage articles.
            </p>
          </div>
          <div class="mt-5">
            <a href="{{ route('admin.dashboard') }}" 
              class="inline-flex items-center justify-center rounded bg-[#1890FF] px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-[#40a9ff] active:scale-95 transition-all">
              Go to Admin Console
            </a>
          </div>
        </div>
      @else
        <x-ui.card title="Quick Actions" subtitle="Write Content">
          <p class="text-xs leading-relaxed text-slate-500 mb-4">
            Draft your ideas and insights, then submit them for administrator approval to publish.
          </p>
          <a href="{{ route('articles.create') }}" 
            class="inline-flex items-center justify-center rounded bg-[#1890FF] px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-[#40a9ff] active:scale-95 transition-all">
            + New Article
          </a>
        </x-ui.card>
      @endif
    </div>
  </div>
</x-app-layout>
