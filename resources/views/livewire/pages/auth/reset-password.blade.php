<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component {
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $user = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
        $this->user = request()->string('user');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $broker = match ($this->user) {
            'staff' => 'staff',
            'parent' => '__parents',
            'user' => 'users',
            default => null,
        };

        $status = Password::broker($broker)->reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }
        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

@section('title', 'Reset Password')
<!-- auth-page wrapper -->
<div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
    <div class="bg-overlay"></div>
    <!-- auth-page content -->
    <div class="auth-page-content overflow-hidden pt-lg-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card overflow-hidden card-bg-fill border-0 card-border-effect-none">
                        <div class="row justify-content-center g-0">
                            @include('livewire.pages.auth.cover')
                            <!-- end col -->

                            <div class="col-lg-6">
                                <div class="p-lg-5 p-4">
                                    <h5 class="text-primary">Create new password</h5>
                                    <p class="text-muted">Your new password must be different from previous used
                                        password.</p>

                                    <div class="p-2">
                                        <form wire:submit.prevent="resetPassword">

                                            <div class="mb-3">
                                                <input type="hidden" wire:model="token" value="{{ $token }}">
                                                <label class="form-label" for="password-input">New Password</label>
                                                <div class="position-relative auth-pass-inputgroup">
                                                    <input wire:model="password" type="password"
                                                           class="form-control pe-5 password-input  @error('password') is-invalid @enderror"
                                                           onpaste="return false"
                                                           placeholder="Enter password" id="password-input"
                                                           aria-describedby="passwordInput"
                                                           required>
                                                    @error('password')<span class="invalid-feedback"
                                                                            role="alert"><strong>{{ $message }}</strong></span> @enderror
                                                    <button
                                                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                        type="button" id="password-addon"><i
                                                            class="ri-eye-fill align-middle"></i></button>
                                                </div>
                                                <div id="passwordInput" class="form-text">Must be at least 8
                                                    characters.
                                                </div>
                                            </div>

                                            <div id="password-contain" class="p-3 bg-light mb-2 rounded">
                                                <h5 class="fs-13">Password must contain:</h5>
                                                <p id="pass-length" class="invalid fs-12 mb-2">Minimum <b>8
                                                        characters</b></p>
                                                <p id="pass-lower" class="invalid fs-12 mb-2">At <b>lowercase</b> letter
                                                    (a-z)</p>
                                                <p id="pass-upper" class="invalid fs-12 mb-2">At least <b>uppercase</b>
                                                    letter (A-Z)</p>
                                                <p id="pass-number" class="invalid fs-12 mb-0">A least <b>number</b>
                                                    (0-9)</p>
                                            </div>

                                            <div class="mt-4">
                                                <x-button class="btn btn-primary w-100" label="Reset Password"/>
                                            </div>

                                        </form>
                                    </div>

                                    <div class="mt-5 text-center">
                                        <p class="mb-0">Wait, I remember my password...
                                            <a wire:navigate href="{{route('login')}}"
                                               class="fw-semibold text-primary text-decoration-underline"> Click
                                                here </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->

            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end auth page content -->

    <!-- footer -->
    @include('livewire.pages.auth.footer')
    <!-- end Footer -->
</div>
<!-- end auth-page-wrapper -->

@section('script')
    <script src="{{ URL::asset('build/js/pages/passowrd-create.init.js') }}"></script>
@endsection
