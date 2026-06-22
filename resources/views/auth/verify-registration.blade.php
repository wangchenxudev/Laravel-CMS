<x-app-layout title="Verify Email">
    <div class="mx-auto max-w-md px-4 sm:px-6 py-12 md:py-20">
        <x-ui.card>
            <div class="border-b border-slate-100 pb-5 mb-6 text-center">
                <span class="inline-flex items-center justify-center rounded-full bg-brand-500/10 p-2 mb-3 text-brand-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v6a2 2 0 002 2z" />
                    </svg>
                </span>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Verify Email</h1>
                <p class="mt-2 text-sm text-slate-500">Enter the six-digit code sent to {{ $email }}</p>
            </div>

            <form method="POST" action="{{ route('register.confirm') }}" class="space-y-5">
                @csrf

                <input type="hidden" name="email" value="{{ $email }}">

                <div>
                    <x-ui.label for="code">Verification code</x-ui.label>
                    <x-ui.input id="code" name="code" type="text" value="{{ old('code') }}" required autofocus inputmode="numeric" autocomplete="one-time-code" maxlength="6" :invalid="$errors->has('code')" />
                    @error('code')
                        <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <x-ui.button type="submit" class="w-full">
                    Verify and Create Account
                </x-ui.button>
            </form>

            <form method="POST" action="{{ route('register.verify.resend') }}" class="mt-4">
                @csrf
                <x-ui.button type="submit" variant="secondary" class="w-full">
                    Send New Code
                </x-ui.button>
            </form>

            <div class="mt-6 text-center text-sm border-t border-slate-100 pt-5">
                <a href="{{ route('register') }}" class="font-semibold text-brand-500 hover:text-brand-600 transition-colors underline">Use a different email</a>
            </div>
        </x-ui.card>
    </div>
</x-app-layout>
