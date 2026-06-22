<x-app-layout title="Reset Password">
    <div class="mx-auto max-w-md px-4 sm:px-6 py-12 md:py-20">
        <x-ui.card>
            <div class="border-b border-slate-100 pb-5 mb-6 text-center">
                <span class="inline-flex items-center justify-center rounded-full bg-brand-500/10 p-2 mb-3 text-brand-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </span>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Set New Password</h1>
                <p class="mt-2 text-sm text-slate-500">Use the six-digit code from your email</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                @csrf

                <div>
                    <x-ui.label for="email">Email</x-ui.label>
                    <x-ui.input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autocomplete="username" :invalid="$errors->has('email')" />
                    @error('email')
                        <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-ui.label for="code">Reset code</x-ui.label>
                    <x-ui.input id="code" name="code" type="text" value="{{ old('code') }}" required inputmode="numeric" autocomplete="one-time-code" maxlength="6" :invalid="$errors->has('code')" />
                    @error('code')
                        <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-ui.label for="password">New password</x-ui.label>
                    <x-ui.input id="password" name="password" type="password" required autocomplete="new-password" :invalid="$errors->has('password')" />
                    @error('password')
                        <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-ui.label for="password_confirmation">Confirm new password</x-ui.label>
                    <x-ui.input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" />
                </div>

                <x-ui.button type="submit" class="w-full">
                    Reset Password
                </x-ui.button>
            </form>

            <div class="mt-6 text-center text-sm border-t border-slate-100 pt-5">
                <a href="{{ route('password.request') }}" class="font-semibold text-brand-500 hover:text-brand-600 transition-colors underline">Send another code</a>
            </div>
        </x-ui.card>
    </div>
</x-app-layout>
