<x-app-layout title="Dashboard">
  @php($user = auth()->user())

  <div class="mx-auto max-w-6xl px-6 py-10">
    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
          @if ($user->isRoot())
            <h1 class="text-xl font-semibold text-slate-950">Root dashboard</h1>
            <p class="mt-2 text-sm text-slate-600">Review admin requests and manage administrator accounts.</p>
          @elseif ($user->isAdmin())
            <h1 class="text-xl font-semibold text-slate-950">Admin dashboard</h1>
            <p class="mt-2 text-sm text-slate-600">Manage regular CMS users and moderation tasks.</p>
          @else
            <h1 class="text-xl font-semibold text-slate-950">User dashboard</h1>
            <p class="mt-2 text-sm text-slate-600">Manage your account and request admin access.</p>
          @endif
        </div>

        <div class="rounded-md border border-slate-200 bg-slate-50 px-4 py-3 text-sm">
          <span class="text-slate-500">Current role</span>
          <span class="ml-2 font-semibold text-slate-950">{{ $user->role }}</span>
        </div>
      </div>

      @if ($user->isRoot())
        <div class="mt-8 grid gap-4 md:grid-cols-3">
          <div class="rounded-md border border-slate-200 p-4">
            <h2 class="text-sm font-semibold text-slate-950">Admin requests</h2>
            <p class="mt-2 text-sm text-slate-600">Pending upgrade requests will be reviewed here.</p>
          </div>
          <div class="rounded-md border border-slate-200 p-4">
            <h2 class="text-sm font-semibold text-slate-950">Admin accounts</h2>
            <p class="mt-2 text-sm text-slate-600">Admin demotion and ban tools will be added later.</p>
          </div>
          <div class="rounded-md border border-slate-200 p-4">
            <h2 class="text-sm font-semibold text-slate-950">System status</h2>
            <p class="mt-2 text-sm text-slate-600">Root-only CMS overview placeholder.</p>
          </div>
        </div>
      @elseif ($user->isAdmin())
        <div class="mt-8 grid gap-4 md:grid-cols-2">
          <div class="rounded-md border border-slate-200 p-4">
            <h2 class="text-sm font-semibold text-slate-950">User management</h2>
            <p class="mt-2 text-sm text-slate-600">User ban and unban tools will be added later.</p>
          </div>
          <div class="rounded-md border border-slate-200 p-4">
            <h2 class="text-sm font-semibold text-slate-950">CMS operations</h2>
            <p class="mt-2 text-sm text-slate-600">Admin content workflow placeholder.</p>
          </div>
        </div>
      @else
        <div class="mt-8 grid gap-4 md:grid-cols-2">
          <div class="rounded-md border border-slate-200 p-4">
            <h2 class="text-sm font-semibold text-slate-950">Account settings</h2>
            <p class="mt-2 text-sm text-slate-600">Open settings to request admin access.</p>
            <a href="{{ route('settings.edit') }}"
              class="mt-4 inline-flex rounded-md bg-slate-950 px-3 py-2 text-sm font-medium text-white hover:bg-slate-800">
              Open settings
            </a>
          </div>
          <div class="rounded-md border border-slate-200 p-4">
            <h2 class="text-sm font-semibold text-slate-950">CMS access</h2>
            <p class="mt-2 text-sm text-slate-600">User-facing CMS tools will be added later.</p>
          </div>
        </div>
      @endif
    </div>
  </div>
</x-app-layout>
