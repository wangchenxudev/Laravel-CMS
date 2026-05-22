<x-app-layout title="Admin Dashboard">
  @php($user = auth()->user())

  <div class="mx-auto max-w-6xl px-6 py-10">
    <div class="border-b border-zinc-200 pb-8">
      <p class="text-sm font-medium text-blue-700">Administration</p>
      <div class="mt-3 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <h1 class="text-3xl font-semibold tracking-tight text-zinc-950">Admin dashboard</h1>
          <p class="mt-2 max-w-2xl text-sm leading-6 text-zinc-600">
            A separate workspace for administrator-only actions and operational visibility.
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
        <h2 class="text-sm font-semibold text-zinc-950">Users</h2>
        <p class="mt-2 text-sm leading-6 text-zinc-600">User management tools will be added after the auth foundation is stable.</p>
      </section>

      <section class="rounded-sm border border-zinc-200 bg-white p-5 shadow-sm">
        <h2 class="text-sm font-semibold text-zinc-950">Access control</h2>
        <p class="mt-2 text-sm leading-6 text-zinc-600">Admin access is currently controlled by the user role field.</p>
      </section>

      <section class="rounded-sm border border-zinc-200 bg-white p-5 shadow-sm">
        <h2 class="text-sm font-semibold text-zinc-950">Operations</h2>
        <p class="mt-2 text-sm leading-6 text-zinc-600">CMS-specific operations are intentionally outside this first step.</p>
      </section>
    </div>
  </div>
</x-app-layout>
