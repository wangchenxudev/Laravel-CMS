<x-app-layout title="Register">
    <div class="mx-auto max-w-md px-4 sm:px-6 py-12 md:py-20">
        <x-ui.card>
            <div class="border-b border-slate-100 pb-5 mb-6 text-center">
                <span class="inline-flex items-center justify-center rounded-full bg-[#1890FF]/10 p-2 mb-3 text-[#1890FF]">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </span>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Create Account</h1>
                <p class="mt-2 text-sm text-slate-500">Registration creates a standard user account</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <x-ui.label for="name">Name</x-ui.label>
                    <x-ui.input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" :invalid="$errors->has('name')" />
                    @error('name')
                        <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-ui.label for="email">Email</x-ui.label>
                    <x-ui.input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" :invalid="$errors->has('email')" />
                    @error('email')
                        <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-ui.label for="password">Password</x-ui.label>
                    <x-ui.input id="password" name="password" type="password" required autocomplete="new-password" :invalid="$errors->has('password')" />
                    @error('password')
                        <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-ui.label for="password_confirmation">Confirm password</x-ui.label>
                    <x-ui.input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" />
                </div>

                <x-ui.button type="submit" class="w-full">
                    Register
                </x-ui.button>
            </form>

            <div class="mt-6 text-center text-sm border-t border-slate-100 pt-5">
                <span class="text-slate-500">Already have an account?</span>
                <a href="{{ route('login') }}" class="font-semibold text-[#1890FF] hover:text-[#40a9ff] transition-colors underline">Login here</a>
            </div>
        </x-ui.card>
    </div>
</x-app-layout>
