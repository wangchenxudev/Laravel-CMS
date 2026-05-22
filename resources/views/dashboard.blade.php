<x-app-layout title="Dashboard">
  @php($user = auth()->user())

  <div class="mx-auto max-w-6xl px-6 py-10">
    <div class="border-b border-zinc-200 pb-8">
      <p class="text-sm font-medium text-blue-700">User workspace</p>
      <div class="mt-3 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <h1 class="text-3xl font-semibold tracking-tight text-zinc-950">Dashboard</h1>
          <p class="mt-2 max-w-2xl text-sm leading-6 text-zinc-600">
            This is the standard account area for registered users.
          </p>
        </div>

        <div class="rounded-sm border border-zinc-200 bg-white px-4 py-3 text-sm shadow-sm">
          <span class="text-zinc-500">Current role</span>
          <span class="ml-2 font-semibold text-zinc-950">{{ $user->role->value }}</span>
        </div>
      </div>
    </div>

    <div class="mt-8 grid gap-4 md:grid-cols-3">
      <section class="rounded-sm border border-zinc-200 bg-white p-5 shadow-sm">
        <h2 class="text-sm font-semibold text-zinc-950">Account status</h2>
        <p class="mt-2 text-sm leading-6 text-zinc-600">Your account is active and ready for the next product workflow.</p>
      </section>

      <section class="rounded-sm border border-zinc-200 bg-white p-5 shadow-sm">
        <h2 class="text-sm font-semibold text-zinc-950">Security</h2>
        <p class="mt-2 text-sm leading-6 text-zinc-600">Password reset support is planned for a later implementation step.</p>
      </section>

      @if ($user->isAdmin())
        <div class="rounded-sm border border-blue-200 bg-blue-50 p-5">
          <h2 class="text-sm font-semibold text-zinc-950">Admin access</h2>
          <p class="mt-2 text-sm leading-6 text-zinc-700">Your account can open the admin dashboard.</p>
          <a href="{{ route('admin.dashboard') }}" class="mt-4 inline-flex rounded-sm bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700">
            Open Admin Dashboard
          </a>
        </div>
      @endif
    </div>
  </div>
</x-app-layout>
