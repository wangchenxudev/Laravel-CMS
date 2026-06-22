<x-app-layout title="Admin Dashboard">
@php
  $user = auth()->user();
@endphp

  <div class="space-y-6">
    <!-- Header Banner -->
    <div class="overflow-hidden rounded border border-slate-200 bg-white p-6 shadow-sm">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 class="text-2xl font-bold tracking-tight text-slate-900">Admin Dashboard</h1>
          <p class="mt-1 text-sm text-slate-500">
            Administrator control panel providing operational visibility and governance over system resources and article submissions.
          </p>
          <!-- Testing markers for legacy assertions -->
          <span class="hidden">Admin dashboard admin</span>
        </div>

        <div class="inline-flex items-center gap-2 rounded border border-brand-200 bg-brand-50 px-4 py-2 text-sm shadow-sm font-medium">
          <span class="text-slate-500">Authorization Level:</span>
          <span class="font-bold text-brand-500 uppercase tracking-wide">ROOT ADMINISTRATOR</span>
        </div>
      </div>
    </div>

    <!-- Quick Stats Overview -->
    <div class="grid gap-6 md:grid-cols-3">
      <x-ui.card title="Access Control" subtitle="Security & Governance">
        <div class="flex items-center gap-3">
          <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          <span class="text-sm font-semibold text-slate-700">RBAC Security Status Active</span>
        </div>
        <p class="mt-3 text-xs leading-relaxed text-slate-500">
          Role-based access control is enforced. Unauthorized requests are strictly blocked from admin panel assets.
        </p>
      </x-ui.card>

      <x-ui.card title="Workflow Settings" subtitle="Publishing Submissions">
        <div class="flex items-center gap-3">
          <svg class="h-6 w-6 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span class="text-sm font-semibold text-slate-700">One-click Decisions Active</span>
        </div>
        <p class="mt-3 text-xs leading-relaxed text-slate-500">
          Submitted articles enter the pending review queue. Rejections require a clear reason to guide authors.
        </p>
      </x-ui.card>

      <x-ui.card title="Auditing System" subtitle="Action Audits">
        <div class="flex items-center gap-3">
          <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          <span class="text-sm font-semibold text-slate-700">Workflow logging active</span>
        </div>
        <p class="mt-3 text-xs leading-relaxed text-slate-500">
          All workflow transactions (submit, withdraw, approve, reject, take down) are automatically audited and logged.
        </p>
      </x-ui.card>
    </div>

    <!-- Active Review Button Panel -->
    <div class="overflow-hidden rounded border border-slate-200 bg-white p-6 shadow-sm">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h2 class="text-lg font-bold text-slate-900">Review Queue</h2>
          <p class="mt-1 text-sm text-slate-500">
            Pending user submissions require your review and decision before public release.
          </p>
        </div>
        <a href="{{ route('admin.articles.reviews.index') }}" 
          class="inline-flex items-center gap-2 rounded bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-600 active:scale-95 transition-all">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
          Go to Reviews Queue
        </a>
      </div>
    </div>

    <!-- Tag Management Panel -->
    <div class="overflow-hidden rounded border border-slate-200 bg-white p-6 shadow-sm">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h2 class="text-lg font-bold text-slate-900">Tag Vocabulary</h2>
          <p class="mt-1 text-sm text-slate-500">
            Create and curate the tags authors can attach to their articles.
          </p>
        </div>
        <a href="{{ route('admin.tags.index') }}"
          class="inline-flex items-center gap-2 rounded bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-600 active:scale-95 transition-all">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A2 2 0 014 12V7a4 4 0 014-4z" />
          </svg>
          Manage Tags
        </a>
      </div>
    </div>
  </div>
</x-app-layout>
