
@section('title', 'Comprehensive School Management System')
@section('css')
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
@endsection


<div class="layout-wrapper landing">
    <livewire:landing.top-bar/>
    <!-- end navbar -->
    <div class="vertical-overlay" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent.show"></div>

    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center mb-5">
                        <h3 class="mb-3 fw-semibold">Apply to Join Our System</h3>
                        <p class="text-muted mb-4 ff-secondary">Submit your school's application to get started.
                            If you have any questions, feel free to contact us, and we'll assist you promptly!</p>
                    </div>
                </div>
            </div>

            <!-- end row -->

            <div class="row g-lg-5 g-4">

                <div class="card">
                    <form wire:submit="submit">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">School Application Form</h5>

                        </div>
                        <div class="card-body">
                            <div class="row g-4">

                                <x-input-text label="School Name" model="school.school_name"/>
                                <x-enum-select enum="App\Enum\Region" label="Region" model="school.region"/>
                                <x-input-text label="District" model="school.district"/>
                                <x-input-text label="Physical Address" model="school.physical_address"/>
                                <x-input-text label="Digital Address" model="school.digital_address"/>
                                <x-input-text label="Postal Address" model="school.postal_address"/>
                                <x-input-text label="Tel Number" type="tel" model="school.tel_number"/>
                                <x-input-text label="Email Address" type="email" model="school.email_address"/>
                                <x-input-text label="Date Established" type="date" model="school.date_established"/>
                                <x-input-text label="Est. Annual Revenue" type="number" model="school.est_revenue"/>
                                <x-enum-select enum="App\Enum\StudentEstimation" label="Est. Students" model="school.est_students"/>
                                <x-enum-select enum="App\Enum\StaffEstimation" label="Est. Staff" model="school.est_staff"/>
                                <x-input-text label="Name of Applicant" model="school.applicant"/>
                                <x-enum-select enum="App\Enum\SchoolPosition" label="Role" model="school.applicant_position"/>
                                <x-input-text label="Applicant's Tel" type="tel" model="school.applicant_phone"/>
                                <x-input-text label="Applicant's Email" type="email" model="school.applicant_email"/>
                                <div class="col-lg-12">
                                    <div class="hstack justify-content-end gap-2">
                                        <x-button label="Submit"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </section>

    <!-- Start footer -->
    <livewire:landing.footer/>
    <!-- end footer -->

    <!--start back-to-top-->
{{--    <button onclick="topFunction()" class="btn btn-danger btn-icon landing-back-top" id="back-to-top">--}}
{{--        <i class="ri-arrow-up-line"></i>--}}
{{--    </button>--}}
    <!--end back-to-top-->

</div>
<!-- end layout wrapper -->

</body>

@section('script')
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/landing.init.js') }}"></script>
@endsection
