<x-app-layout title="Register">
    <div class="mx-auto max-w-md px-6 py-12">
        <div class="border-b border-zinc-200 pb-6">
            <p class="text-sm font-medium text-blue-700">Get started</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-zinc-950">Create account</h1>
            <p class="mt-2 text-sm leading-6 text-zinc-600">Registration creates a standard user account.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-zinc-700">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" class="mt-2 w-full rounded-sm border border-zinc-300 bg-white px-3 py-2 text-sm outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-zinc-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" class="mt-2 w-full rounded-sm border border-zinc-300 bg-white px-3 py-2 text-sm outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-zinc-700">Password</label>
                <input id="password" name="password" type="password" required autocomplete="new-password" class="mt-2 w-full rounded-sm border border-zinc-300 bg-white px-3 py-2 text-sm outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-zinc-700">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="mt-2 w-full rounded-sm border border-zinc-300 bg-white px-3 py-2 text-sm outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
            </div>

            <button type="submit" class="w-full rounded-sm bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                Register
            </button>
        </form>

        <p class="mt-5 text-sm text-zinc-600">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-blue-700 underline">Login</a>
        </p>
    </div>
</x-app-layout>
