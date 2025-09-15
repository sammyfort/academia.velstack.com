<?php

use App\Enum\LoginType;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\{_Parent, Staff, User};

new #[Layout('layouts.auth')] class extends Component {
    public string $email = '';
   public string $user_type = LoginType::STAFF->value;

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
            'user_type' => ['required', Rule::in(LoginType::cases())]
        ]);

        $user = match($this->user_type) {
            LoginType::STAFF->value => Staff::where('email', $this->email)->first(),
            LoginType::PARENT->value => _Parent::where('email', $this->email)->first(),
            LoginType::ADMIN->value => User::where('email', $this->email)->first(),
            default => null,
        };

        if ($user) {
            try {
                $token = Password::broker()->createToken($user);
                $user->sendPasswordResetNotification($token);
            } catch (Exception $e) {
                info($e);
                session()->flash('error', "Something went wrong");
            }
        }
        session()->flash('success', "If your email is correct,
        you should receive instructions in your mail. please check your inbox");
        $this->reset('email');

    }
};

?>



@section('title', 'Forgot Password')
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
                                    <h5 class="text-primary">Forgot Password?</h5>
                                    <p class="text-muted">Reset password with {{config('app.name')}}.
                                        Enter your email address instructions will be sent to you! </p>

                                    <div class="mt-2 text-center">
                                        <lord-icon src="https://cdn.lordicon.com/rhvddzym.json" trigger="loop"
                                                   colors="primary:#8c68cd" class="avatar-xl">
                                        </lord-icon>
                                    </div>

                                    @if(session()->has('success'))
                                        <div class="alert alert-borderless alert-success text-center mb-2 mx-2"
                                             role="alert">
                                            {{session('success')}}
                                        </div>
                                    @elseif(session()->has('error'))
                                        <div class="alert alert-borderless alert-danger text-center mb-2 mx-2"
                                             role="alert">
                                            {{session('error')}}
                                        </div>
                                    @endif

                                    <div class="p-2">
                                        <form wire:submit.prevent="sendPasswordResetLink">
                                            <x-enum-select enum="App\Enum\LoginType" model="user_type"
                                                           label="Request As" wrapper_class="col-md-12"/>
                                            <x-input-text model="email" type="email" label="Email"
                                                          wrapper_class="col-md-12"/>

                                            <div class="text-center mt-4">
                                                <x-button class="btn btn-primary w-100" label="Send Reset Link"/>
                                            </div>
                                        </form><!-- end form -->
                                    </div>

                                    <div class="mt-5 text-center">
                                        <p class="mb-0">Wait, I remember my password... <a
                                                href="{{route('login')}}" wire:navigate
                                                class="fw-semibold text-primary text-decoration-underline"> Click here
                                            </a></p>
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
