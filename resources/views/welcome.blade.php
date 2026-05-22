<x-app-layout title="Welcome">
  <div class="mx-auto flex min-h-[72vh] max-w-6xl items-center px-6 py-16">
    <div class="w-full max-w-3xl">
      <p class="text-sm font-medium text-blue-700">Account access</p>
      <h1 class="mt-3 text-4xl font-semibold tracking-tight text-zinc-950">A focused login and registration foundation.</h1>
      <p class="mt-5 max-w-2xl text-base leading-7 text-zinc-600">
        Create a user account, sign in, and move into a clean workspace. Admin accounts receive a separate dashboard
        with role-based access control.
      </p>

      <div class="mt-8 flex flex-wrap gap-3">
        @guest
          <a href="{{ route('register') }}"
            class="rounded-sm bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
            Register
          </a>
          <a href="{{ route('login') }}"
            class="rounded-sm border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-white">
            Login
          </a>
        @else
          <a href="{{ route('dashboard') }}"
            class="rounded-sm bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
            Go to dashboard
          </a>
        @endguest
      </div>
    </div>
  </div>
</x-app-layout>
