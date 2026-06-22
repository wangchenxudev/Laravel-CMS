<x-app-layout title="Settings">
@php
  $user = auth()->user();
@endphp

  <div class="mx-auto max-w-6xl">
    <div class="mb-6">
      <h1 class="text-2xl font-bold tracking-tight text-slate-900">Settings</h1>
      <p class="mt-1 text-sm text-slate-500">Manage your profile, account access, and password security.</p>
    </div>

    <div class="grid grid-cols-[176px_minmax(0,1fr)] gap-4 sm:grid-cols-[220px_minmax(0,1fr)] sm:gap-6">
      <aside class="sticky top-24 self-start">
        <nav class="overflow-hidden rounded border border-slate-200 bg-white shadow-sm" aria-label="Account settings sections">
          <a href="#account-information" class="flex items-center gap-3 border-l-4 border-brand-500 bg-brand-500/5 px-4 py-3 text-sm font-semibold text-slate-900">
            <svg class="h-5 w-5 shrink-0 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 0115 0" />
            </svg>
            Account Information
          </a>
          <a href="#password-security" class="flex items-center gap-3 border-l-4 border-transparent px-4 py-3 text-sm font-semibold text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900">
            <svg class="h-5 w-5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 00-9 0v3.75m-.75 11.25h10.5A2.25 2.25 0 0019.5 19.5v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
            Password Security
          </a>
        </nav>
      </aside>

      <div class="space-y-6">
        <section id="account-information" class="scroll-mt-24">
          <x-ui.card title="Account Information" subtitle="View your profile details and account role.">
            <div class="grid gap-5 md:grid-cols-2">
              <div>
                <x-ui.label for="name">Username</x-ui.label>
                <x-ui.input id="name" type="text" value="{{ $user->name }}" readonly class="bg-slate-50 cursor-not-allowed border-slate-200 text-slate-500" />
                <p class="mt-1.5 text-xs text-slate-400">Username modifications are currently disabled.</p>
              </div>

              <div>
                <x-ui.label for="role">Access Role</x-ui.label>
                <x-ui.input id="role" type="text" value="{{ $user->role->value === 'admin' ? 'Administrator' : 'Editor' }}" readonly class="bg-slate-50 cursor-not-allowed border-slate-200 text-slate-500 font-semibold text-brand-500" />
              </div>

              <div class="md:col-span-2">
                <x-ui.label for="email">Email Address</x-ui.label>
                <x-ui.input id="email" type="email" value="{{ $user->email }}" readonly class="bg-slate-50 cursor-not-allowed border-slate-200 text-slate-500" />
                <p class="mt-1.5 text-xs text-slate-400">Your email address is verified. Contact support to request a change.</p>
              </div>
            </div>
          </x-ui.card>
        </section>

        <section id="password-security" class="scroll-mt-24">
          <x-ui.card title="Password Security" subtitle="Update your password or recover access with an email code.">
            <form method="POST" action="{{ route('settings.password.update') }}" class="space-y-5">
              @csrf
              @method('PUT')

              <div class="grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                  <x-ui.label for="current_password">Current password</x-ui.label>
                  <x-ui.input id="current_password" name="current_password" type="password" required autocomplete="current-password" :invalid="$errors->has('current_password')" />
                  @error('current_password')
                    <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <x-ui.label for="password">New password</x-ui.label>
                  <x-ui.input id="password" name="password" type="password" required autocomplete="new-password" :invalid="$errors->has('password')" />
                  @error('password')
                    <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <x-ui.label for="password_confirmation">Confirm new password</x-ui.label>
                  <x-ui.input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" />
                </div>
              </div>

              <div class="border-t border-slate-100 pt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-brand-500 hover:text-brand-600 transition-colors underline">Recover with email code</a>
                <x-ui.button type="submit" variant="primary">Update Password</x-ui.button>
              </div>
            </form>
          </x-ui.card>
        </section>
      </div>
    </div>
  </div>
</x-app-layout>
