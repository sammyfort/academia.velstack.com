@section('title', 'Comprehensive School Management System')
@section('css')
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
<body data-bs-spy="scroll" data-bs-target="#navbar-example">
    <body data-bs-spy="scroll" data-bs-target="#navbar-example">

    <div class="layout-wrapper landing">
       <livewire:landing.top-bar/>
        <!-- end navbar -->
        <div class="vertical-overlay" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent.show"></div>

        <!-- start hero section -->
        <livewire:landing.hero/>
        <!-- end hero section -->

        <!-- start client section -->
{{--        <livewire:landing.client/>--}}
        <!-- end client section -->

        <!-- start services -->
        <livewire:landing.features/>
        <!-- end services -->

        <!-- start features -->
        <livewire:landing.services/>
        <!-- end features -->


        <!-- start cta -->
        <section class="py-5 bg-primary position-relative bg-opacity-50">
            <div class="bg-overlay bg-overlay-pattern opacity-50"></div>
            <div class="container">
                <div class="row align-items-center gy-4">
                    <div class="col-sm">
                        <div>
                            <h4 class="text-white mb-0 fw-semibold">Build your School Management System with Our Comprehensive Dashboard</h4>

                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-sm-auto">
                        <div>
                            <a href="{{route('apply')}}"   class="btn bg-gradient btn-danger"><i class="ri-focus-2-line align-middle me-1"></i> Start Now</a>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </section>
        <!-- end cta -->

        <!-- start plan -->
{{--        <livewire:landing.plan/>--}}
        <!-- end plan -->

        <!-- start faqs -->
{{--        <livewire:landing.faqs/>--}}

        <!-- end faqs -->


        <!-- start review -->
{{--        <livewire:landing.testimonies/>--}}

        <!-- end review -->


        <!-- start counter -->
{{--        <livewire:landing.counter/>--}}
        <!-- end counter -->

        <!-- start Work Process -->
        <livewire:landing.process/>
        <!-- end Work Process -->


        <!-- start team -->
{{--        <livewire:landing.team/>--}}
        <!-- end team -->

        <!-- start contact -->
{{--        <livewire:landing.contact/>--}}
        <!-- end contact -->

        <!-- Start footer -->
        <livewire:landing.footer/>
        <!-- end footer -->

        <!--start back-to-top-->
        <button onclick="topFunction()" class="btn btn-danger btn-icon landing-back-top" id="back-to-top">
            <i class="ri-arrow-up-line"></i>
        </button>
        <!--end back-to-top-->

    </div>
    <!-- end layout wrapper -->

    </body>

@section('script')
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/landing.init.js') }}"></script>
@endsection
