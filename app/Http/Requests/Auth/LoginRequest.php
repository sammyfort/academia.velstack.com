<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

use App\Enums\LoginType;
/**
 * @property string $email
 * @property string $password
 */
class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
            'login_as' => ['required', Rule::in(LoginType::toArray())]
        ];
    }

    public function authenticate(): void
    {
         $this->ensureIsNotRateLimited();

    $credentials = [
        'email'    => $this->email,
        'password' => $this->password,
    ];

    $mobileCredentials = [
        'mobile'   => $this->email,
        'password' => $this->password,
    ];

    $remember = $this->boolean('remember');

    $guard = match ($this->login_as) {
        LoginType::ADMIN->value  => 'web', 
        LoginType::PARENT->value => 'parent',
        default                  => 'staff',
    };

     

    // Try login with email OR mobile
    if (
        ! Auth::guard($guard)->attempt($credentials, $remember) &&
        ! Auth::guard($guard)->attempt($mobileCredentials, $remember)
    ) {
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'form.email' => trans('auth.failed'),
        ]);
    }

    RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
