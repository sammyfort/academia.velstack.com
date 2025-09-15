<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Validation\ValidationException;

new #[Layout('layouts.auth')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */

    public function authenticate(): void
    {
        $this->validate();
        $this->form->login();
        Session::regenerate();

    }
};
?>



@section('title', 'Login')
<!-- auth-page wrapper -->
<div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
    <div class="bg-overlay"></div>
    <!-- auth-page content -->
    <div class="auth-page-content overflow-hidden pt-lg-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card overflow-hidden card-bg-fill border-0 card-border-effect-none">
                        <div class="row g-0">
                            @include('livewire.pages.auth.cover')
                            <!-- end col -->

                            <div class="col-lg-6">
                                <div class="p-lg-5 p-4">
                                    <div>
                                        <h5 class="text-primary">Welcome Back !</h5>
                                        <p class="text-muted">Sign in to continue to OpenPortal.</p>
                                    </div>

                                    @if(session()->has('status'))
                                        <div class="alert alert-borderless alert-success text-center mb-2 mx-2"
                                             role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @endif

                                    @error('throttle')
                                    <div
                                        x-data="{
                                           timeLeft: {{ preg_match('/(\d+)/', $message, $matches) ? $matches[1] : 0 }},
                                             get displayTime() {
                                             const minutes = Math.floor(this.timeLeft / 60);
                                             const seconds = this.timeLeft % 60;
                                             return `${minutes}:${seconds.toString().padStart(2, '0')}`;
                                            }
                                          }"
                                        x-init="setInterval(() => timeLeft > 0 ? timeLeft-- : null, 1000)"
                                        class="alert alert-danger"
                                        x-show="timeLeft > 0"
                                    >
                                        <span id="throttle-error">We have blocked this request because of too many wrong attempts. Please try again in <span
                                                x-text="displayTime"></span></span>
                                    </div>
                                    @enderror

                                    <div class="mt-4">
                                        <form wire:submit.prevent="authenticate">
                                            <x-enum-select enum="App\Enum\LoginType" model="form.login_as"
                                                           label="Login As" wrapper_class="col-md-12"/>
                                            <x-input-text model="form.email" type="email" label="Email"
                                                          wrapper_class="col-md-12"/>

                                            <div class="mb-3">
                                                <div class="float-end">
                                                    <a wire:navigate href="{{ route('password.request') }}"
                                                       class="text-muted">Forgot password?</a>
                                                </div>
                                                <label class="form-label" for="password-input">Password</label>
                                                <div class="position-relative auth-pass-inputgroup mb-3">
                                                    <input wire:model="form.password" type="password"
                                                           class="form-control pe-5 password-input" id="password-input">
                                                    <button
                                                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                        type="button" id="password-addon">
                                                        <i class="ri-eye-fill align-middle"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="form-check">
                                                <input wire:model="form.remember" class="form-check-input"
                                                       type="checkbox" id="auth-remember-check">
                                                <label class="form-check-label" for="auth-remember-check">Remember
                                                    me</label>
                                            </div>

                                            <div class="mt-4">
                                                <x-button label="Sign In" class="btn btn-primary w-100" bg="primary"/>
                                            </div>
                                        </form>
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
    <script src="{{ URL::asset('build/js/pages/password-addon.init.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var errorSpan = document.getElementById('throttle-error');
            if (!errorSpan) return;

            // Extract the numeric value from the error message.
            // Example message: "Too many login attempts. Please try again in 55 seconds."
            var messageText = errorSpan.textContent;
            var matches = messageText.match(/(\d+)/);
            if (!matches) return;

            var timeLeft = parseInt(matches[1]);

            // Update the error message immediately (in case the value has changed)
            function updateMessage() {
                if (timeLeft <= 0) {
                    errorSpan.textContent = "You can now try again.";
                    clearInterval(timer);
                } else {
                    // Display time in minutes and seconds if needed:
                    var minutes = Math.floor(timeLeft / 60);
                    var seconds = timeLeft % 60;
                    // Build a new message string:
                    errorSpan.textContent = "Too many login attempts. Please try again in " + minutes + "m " + seconds + "s.";
                }
            }

            // Initial update
            updateMessage();

            // Use setInterval to update every 60 seconds.
            var timer = setInterval(function () {
                timeLeft = Math.max(0, timeLeft - 60);
                updateMessage();
            }, 60000);
        });
    </script>

@endsection

