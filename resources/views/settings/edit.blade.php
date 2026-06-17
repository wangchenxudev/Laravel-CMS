<x-app-layout title="Settings">
@php
  $user = auth()->user();
@endphp

  <div class="max-w-2xl">
    <x-ui.card title="Account Information" subtitle="View or update your account details.">
      <div class="space-y-6">
        <div>
          <x-ui.label for="name">Username</x-ui.label>
          <x-ui.input id="name" type="text" value="{{ $user->name }}" readonly class="bg-slate-50 cursor-not-allowed border-slate-200 text-slate-500" />
          <p class="mt-1.5 text-xs text-slate-400">Username modifications are currently disabled.</p>
        </div>

        <div>
          <x-ui.label for="email">Email Address</x-ui.label>
          <x-ui.input id="email" type="email" value="{{ $user->email }}" readonly class="bg-slate-50 cursor-not-allowed border-slate-200 text-slate-500" />
          <p class="mt-1.5 text-xs text-slate-400">Your email address is verified. Contact support to request a change.</p>
        </div>

        <div>
          <x-ui.label for="role">Access Role</x-ui.label>
          <x-ui.input id="role" type="text" value="{{ $user->role->value === 'admin' ? 'Administrator' : 'Editor' }}" readonly class="bg-slate-50 cursor-not-allowed border-slate-200 text-slate-500 font-semibold text-[#1890FF]" />
        </div>

        <div class="border-t border-slate-100 pt-5 flex items-center justify-end gap-3">
          <x-ui.button variant="secondary" onclick="history.back()">Back</x-ui.button>
          <x-ui.button variant="primary" class="bg-slate-400 border-slate-400 hover:bg-slate-400 cursor-not-allowed active:scale-100" disabled>Save (Coming Soon)</x-ui.button>
        </div>
      </div>
    </x-ui.card>
  </div>
</x-app-layout>
