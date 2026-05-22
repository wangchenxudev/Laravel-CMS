<x-app-layout title="Login">
    <div class="mx-auto max-w-md px-6 py-12">
        <div class="border-b border-zinc-200 pb-6">
            <p class="text-sm font-medium text-blue-700">Welcome back</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-zinc-950">Login</h1>
            <p class="mt-2 text-sm leading-6 text-zinc-600">Sign in to continue to your workspace.</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-zinc-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="mt-2 w-full rounded-sm border border-zinc-300 bg-white px-3 py-2 text-sm outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-zinc-700">Password</label>
                <input id="password" name="password" type="password" required autocomplete="current-password" class="mt-2 w-full rounded-sm border border-zinc-300 bg-white px-3 py-2 text-sm outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-zinc-600">
                <input name="remember" type="checkbox" value="1" class="rounded border-zinc-300 text-blue-600 focus:ring-blue-600">
                Remember me
            </label>

            <button type="submit" class="w-full rounded-sm bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                Login
            </button>
        </form>

        <p class="mt-5 text-sm text-zinc-600">
            Need an account?
            <a href="{{ route('register') }}" class="font-medium text-blue-700 underline">Register</a>
        </p>
    </div>
</x-app-layout>
