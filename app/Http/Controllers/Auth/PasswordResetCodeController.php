<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use App\Notifications\Auth\PasswordResetCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetCodeController extends Controller
{
    private const ResetCodeSentStatus = 'If an account exists for that email, a reset code has been sent.';

    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(ForgotPasswordRequest $request): RedirectResponse
    {
        $email = $request->validated('email');

        $this->ensureIsNotRateLimited($request, 'password-reset-request', $email, 'email', 3);
        RateLimiter::hit($this->throttleKey($request, 'password-reset-request', $email), 60);

        $user = User::query()->where('email', $email)->first();

        if ($user instanceof User) {
            $code = $this->generateCode();

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $email],
                [
                    'token' => Hash::make($code),
                    'created_at' => now(),
                ],
            );

            $user->notify(new PasswordResetCode($code, $this->resetCodeExpiresInMinutes()));
        }

        $request->session()->put('password_reset_email', $email);

        return redirect()->route('password.reset')->with('status', self::ResetCodeSentStatus);
    }

    public function edit(): View
    {
        return view('auth.reset-password', [
            'email' => old('email', session('password_reset_email')),
        ]);
    }

    public function update(ResetPasswordRequest $request): RedirectResponse
    {
        $attributes = $request->validated();
        $email = $attributes['email'];

        $this->ensureIsNotRateLimited($request, 'password-reset-verify', $email, 'code', 5);

        $tokenRecord = DB::table('password_reset_tokens')->where('email', $email)->first();
        $user = User::query()->where('email', $email)->first();

        if (
            ! $user instanceof User
            || $tokenRecord === null
            || $this->resetTokenIsExpired($tokenRecord->created_at)
            || ! Hash::check($attributes['code'], $tokenRecord->token)
        ) {
            RateLimiter::hit($this->throttleKey($request, 'password-reset-verify', $email), 60);

            throw ValidationException::withMessages([
                'code' => 'The reset code is invalid or has expired.',
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($attributes['password']),
        ])->save();

        DB::table('password_reset_tokens')->where('email', $email)->delete();
        RateLimiter::clear($this->throttleKey($request, 'password-reset-verify', $email));
        $request->session()->forget('password_reset_email');

        if (Auth::check()) {
            return redirect()->route('settings.edit')->with('status', 'Your password has been reset.');
        }

        return redirect()->route('login')->with('status', 'Your password has been reset. You can now log in.');
    }

    private function ensureIsNotRateLimited(Request $request, string $action, string $email, string $field, int $maxAttempts): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request, $action, $email), $maxAttempts)) {
            return;
        }

        throw ValidationException::withMessages([
            $field => 'Please wait before trying again.',
        ]);
    }

    private function resetTokenIsExpired(mixed $createdAt): bool
    {
        if ($createdAt === null) {
            return true;
        }

        return Carbon::parse($createdAt)->addMinutes($this->resetCodeExpiresInMinutes())->isPast();
    }

    private function resetCodeExpiresInMinutes(): int
    {
        return (int) config('auth.passwords.users.expire', 60);
    }

    private function throttleKey(Request $request, string $action, string $email): string
    {
        return $action.':'.hash('sha256', $email.'|'.$request->ip());
    }

    private function generateCode(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
