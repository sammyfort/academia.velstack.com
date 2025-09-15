<div>
    <nav class="navbar navbar-expand-lg navbar-landing fixed-top" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="/">
                                    <img src="{{defaultIcon()}}" class="card-logo card-logo-dark" alt="logo dark" height="25">
                                    <img src="{{defaultIcon('white')}}" class="card-logo card-logo-light" alt="logo light" height="25">
            </a>
            <button class="navbar-toggler py-0 fs-20 text-body" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="mdi mdi-menu"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto mt-2 mt-lg-0" id="navbar-example">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('home')}}">Home</a>
                    </li>
                    @if(\Illuminate\Support\Facades\Route::is('home'))
                        <li class="nav-item">
                            <a class="nav-link" href="#services">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#features">Features</a>
                        </li>
                        {{--                    <li class="nav-item">--}}
                        {{--                        <a class="nav-link" href="#plans">Plans</a>--}}
                        {{--                    </li>--}}
                        {{--                    <li class="nav-item">--}}
                        {{--                        <a class="nav-link" href="#reviews">Reviews</a>--}}
                        {{--                    </li>--}}
                        {{--                    <li class="nav-item">--}}
                        {{--                        <a class="nav-link" href="#team">Team</a>--}}
                        {{--                    </li>--}}
                        {{--                    <li class="nav-item">--}}
                        {{--                        <a class="nav-link" href="#contact">Contact</a>--}}
                        {{--                    </li>--}}
                    @endif

                </ul>

                <div class="">
                    <a href="{{route('login')}}" class="btn btn-primary fw-medium">Sign in</a>
                    <a href="{{route('apply')}}" class="btn btn-success">Apply</a>
                </div>
            </div>

        </div>
    </nav>
</div>
