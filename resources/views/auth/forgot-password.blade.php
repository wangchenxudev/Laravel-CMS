<x-app-layout title="Forgot Password">
    <div class="mx-auto max-w-md px-4 sm:px-6 py-12 md:py-20">
        <x-ui.card>
            <div class="border-b border-slate-100 pb-5 mb-6 text-center">
                <span class="inline-flex items-center justify-center rounded-full bg-brand-500/10 p-2 mb-3 text-brand-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H3v-4.586l6.257-6.257A6 6 0 1121 9z" />
                    </svg>
                </span>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Reset Password</h1>
                <p class="mt-2 text-sm text-slate-500">Enter your email and we will send a six-digit reset code</p>
            </div>

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <div>
                    <x-ui.label for="email">Email</x-ui.label>
                    <x-ui.input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" :invalid="$errors->has('email')" />
                    @error('email')
                        <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <x-ui.button type="submit" class="w-full">
                    Send Reset Code
                </x-ui.button>
            </form>

            <div class="mt-6 text-center text-sm border-t border-slate-100 pt-5">
                <a href="{{ route('login') }}" class="font-semibold text-brand-500 hover:text-brand-600 transition-colors underline">Back to login</a>
            </div>
        </x-ui.card>
    </div>
</x-app-layout>
