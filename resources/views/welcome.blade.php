<x-app-layout title="Welcome">
  <div class="mx-auto flex min-h-[70vh] max-w-4xl items-center px-6 py-16">
    <div class="w-full">
      <p class="text-sm font-medium uppercase tracking-wide text-slate-500">RoleGuard CMS</p>
      <h1 class="mt-3 text-3xl font-semibold text-slate-950">A minimal CMS account system.</h1>
      <p class="mt-4 max-w-2xl text-base text-slate-600">
        Register a user account, sign in, and continue to the dashboard. Admin upgrade and root workflows will be added
        in later steps.
      </p>

      <div class="mt-8 flex flex-wrap gap-3">
        @guest
          <a href="{{ route('register') }}"
            class="rounded-md bg-slate-950 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
            Register
          </a>
          <a href="{{ route('login') }}"
            class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
            Login
          </a>
        @else
          <a href="{{ route('dashboard') }}"
            class="rounded-md bg-slate-950 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
            Go to dashboard
          </a>
        @endguest
      </div>
    </div>
  </div>
</x-app-layout>
