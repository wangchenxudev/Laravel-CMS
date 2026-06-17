<x-app-layout title="Login">
    <div class="mx-auto max-w-md px-4 sm:px-6 py-12 md:py-20">
        <x-ui.card>
            <div class="border-b border-slate-100 pb-5 mb-6 text-center">
                <span class="inline-flex items-center justify-center rounded-full bg-[#1890FF]/10 p-2 mb-3 text-[#1890FF]">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </span>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 font-sans">Login</h1>
                <p class="mt-2 text-sm text-slate-500">Sign in to continue to your workspace</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <x-ui.label for="email">Email</x-ui.label>
                    <x-ui.input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" :invalid="$errors->has('email')" />
                    @error('email')
                        <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <x-ui.label for="password">Password</x-ui.label>
                    </div>
                    <x-ui.input id="password" name="password" type="password" required autocomplete="current-password" :invalid="$errors->has('password')" />
                    @error('password')
                        <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-[#1890FF] focus:ring-[#1890FF] transition-colors">
                    <label for="remember" class="ml-2 block text-sm font-medium text-slate-600">Remember me</label>
                </div>

                <x-ui.button type="submit" class="w-full">
                    Login
                </x-ui.button>
            </form>

            <div class="mt-6 text-center text-sm border-t border-slate-100 pt-5">
                <span class="text-slate-500">Need an account?</span>
                <a href="{{ route('register') }}" class="font-semibold text-[#1890FF] hover:text-[#40a9ff] transition-colors underline">Register here</a>
            </div>
        </x-ui.card>
    </div>
</x-app-layout>
