<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\VerifyRegistrationRequest;
use App\Models\User;
use App\Notifications\Auth\RegistrationVerificationCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    private const RegistrationCodeExpiresInMinutes = 15;

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $attributes = $request->validated();
        $email = $attributes['email'];

        $this->ensureIsNotRateLimited($request, 'registration-email', $email, 'email', 3);
        RateLimiter::hit($this->throttleKey($request, 'registration-email', $email), 60);

        $this->storePendingRegistration($attributes);
        $request->session()->put('pending_registration_email', $email);

        return redirect()->route('register.verify')->with('status', 'We sent a six-digit verification code to your email.');
    }

    public function verify(): View|RedirectResponse
    {
        $email = session('pending_registration_email');

        if (! is_string($email) || ! Cache::has($this->pendingRegistrationKey($email))) {
            return redirect()->route('register')->with('status', 'Start registration again to receive a verification code.');
        }

        return view('auth.verify-registration', [
            'email' => $email,
        ]);
    }

    public function confirm(VerifyRegistrationRequest $request): RedirectResponse
    {
        $attributes = $request->validated();
        $email = $attributes['email'];
        $pendingRegistration = Cache::get($this->pendingRegistrationKey($email));

        if (! is_array($pendingRegistration)) {
            throw ValidationException::withMessages([
                'code' => 'The verification code is invalid or has expired.',
            ]);
        }

        $this->ensureIsNotRateLimited($request, 'registration-verify', $email, 'code', 5);

        if (! Hash::check($attributes['code'], $pendingRegistration['code_hash'])) {
            RateLimiter::hit($this->throttleKey($request, 'registration-verify', $email), 60);

            throw ValidationException::withMessages([
                'code' => 'The verification code is invalid or has expired.',
            ]);
        }

        $user = User::create([
            'name' => $pendingRegistration['name'],
            'email' => $pendingRegistration['email'],
            'password' => $pendingRegistration['password'],
            'role' => $pendingRegistration['role'],
        ]);

        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        Cache::forget($this->pendingRegistrationKey($email));
        RateLimiter::clear($this->throttleKey($request, 'registration-verify', $email));
        $request->session()->forget('pending_registration_email');

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->to(route('published.articles.index', absolute: false))
            ->with('status', 'Account created successfully.');
    }

    public function resend(Request $request): RedirectResponse
    {
        $email = $request->session()->get('pending_registration_email');

        if (! is_string($email)) {
            return redirect()->route('register')->with('status', 'Start registration again to receive a verification code.');
        }

        $pendingRegistration = Cache::get($this->pendingRegistrationKey($email));

        if (! is_array($pendingRegistration)) {
            return redirect()->route('register')->with('status', 'Start registration again to receive a verification code.');
        }

        $this->ensureIsNotRateLimited($request, 'registration-resend', $email, 'email', 3);
        RateLimiter::hit($this->throttleKey($request, 'registration-resend', $email), 60);

        $this->refreshPendingRegistrationCode($email, $pendingRegistration);

        return redirect()->route('register.verify')->with('status', 'We sent a new verification code to your email.');
    }

    /**
     * @param  array{name: string, email: string, password: string}  $attributes
     */
    private function storePendingRegistration(array $attributes): void
    {
        $code = $this->generateCode();

        Cache::put($this->pendingRegistrationKey($attributes['email']), [
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => Hash::make($attributes['password']),
            'role' => UserRole::User->value,
            'code_hash' => Hash::make($code),
        ], now()->addMinutes(self::RegistrationCodeExpiresInMinutes));

        Notification::route('mail', $attributes['email'])
            ->notify(new RegistrationVerificationCode($code, self::RegistrationCodeExpiresInMinutes));
    }

    /**
     * @param  array{name: string, email: string, password: string, role: string, code_hash: string}  $pendingRegistration
     */
    private function refreshPendingRegistrationCode(string $email, array $pendingRegistration): void
    {
        $code = $this->generateCode();
        $pendingRegistration['code_hash'] = Hash::make($code);

        Cache::put($this->pendingRegistrationKey($email), $pendingRegistration, now()->addMinutes(self::RegistrationCodeExpiresInMinutes));

        Notification::route('mail', $email)
            ->notify(new RegistrationVerificationCode($code, self::RegistrationCodeExpiresInMinutes));
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

    private function pendingRegistrationKey(string $email): string
    {
        return 'pending-registration:'.hash('sha256', $email);
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
