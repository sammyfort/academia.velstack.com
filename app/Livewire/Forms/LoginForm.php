<?php

namespace App\Livewire\Forms;

use App\Enum\LoginType;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|string')]
    public string $login_as = LoginType::STAFF->value;

    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function login(): void
    {

        $this->ensureIsNotRateLimited();


        if ($this->login_as === LoginType::ADMIN->value) {
            if (!Auth::guard()->attempt($this->only(['email', 'password']), $this->remember)) {
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'form.email' =>  trans('auth.failed'),
                ]);
            }
            RateLimiter::clear($this->throttleKey());
            redirect()->intended(route('admin.dashboard'));

        }elseif($this->login_as === LoginType::PARENT->value){
            if (!Auth::guard('parent')->attempt($this->only(['email', 'password']), $this->remember)) {
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'form.email' =>  trans('auth.failed'),
                ]);
            }
            RateLimiter::clear($this->throttleKey());
            redirect()->intended(route('parents.welcome'));

        }else{
            if (!Auth::guard('staff')->attempt($this->only(['email', 'password']), $this->remember)) {
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'form.email' =>  trans('auth.failed'),
                ]);
            }
            RateLimiter::clear($this->throttleKey());
            redirect()->intended(route('getting.started'));
        }

    }


    protected function ensureIsNotRateLimited(): void
    {

        if (RateLimiter::attempt($this->throttleKey(), 3,
            function() {
                return;
            },
            30 * 60
        )) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'throttle' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }


    /**
     * Get the authentication rate limiting throttle key.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}
