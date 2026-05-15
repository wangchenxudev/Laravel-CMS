<x-app-layout title="Settings">
  @php($user = auth()->user())

  <div class="mx-auto max-w-3xl px-6 py-10">
    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
      <div class="border-b border-slate-200 pb-5">
        <h1 class="text-xl font-semibold text-slate-950">Settings</h1>
        <p class="mt-2 text-sm text-slate-600">Only admin upgrade requests are available in this step.</p>
      </div>

      <section class="mt-6">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
          <div>
            <h2 class="text-base font-semibold text-slate-950">Admin upgrade request</h2>
            <p class="mt-1 text-sm text-slate-600">Enter the invitation code to submit an admin request.</p>
          </div>

          <div class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
            <span class="text-slate-500">Current role</span>
            <span class="ml-2 font-semibold text-slate-950">{{ $user->role }}</span>
          </div>
        </div>

        @if ($user->hasPendingAdminUpgradeRequest())
          <div class="mt-6 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            Your admin upgrade request was submitted at
            {{ $user->admin_upgrade_requested_at->format('Y-m-d H:i') }}.
          </div>
        @elseif ($user->isUser())
          <form method="POST" action="{{ route('settings.admin-upgrade-request.store') }}" class="mt-6 space-y-4">
            @csrf

            <div>
              <label for="invitation_code" class="block text-sm font-medium text-slate-700">Invitation code</label>
              <input id="invitation_code" name="invitation_code" type="text" value="{{ old('invitation_code') }}"
                required
                class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-slate-950 focus:ring-1 focus:ring-slate-950">
              @error('invitation_code')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <button type="submit" class="rounded-md bg-slate-950 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
              Submit request
            </button>
          </form>
        @else
          <div class="mt-6 rounded-md border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
            Admin upgrade requests are only available to regular user accounts.
          </div>
        @endif
      </section>
    </div>
  </div>
</x-app-layout>
