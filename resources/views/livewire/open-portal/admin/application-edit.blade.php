@section('title', "Edit Application")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="update">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Edit Application ({{$application->school_name}})</h5>
                    <div class="d-flex gap-2">

                        <x-link label="Applications" route="admin.applications" class="btn btn-info add-bt " icon="ri-grid-fill me-1 align-bottom"/>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <x-enum-select enum="App\Enum\ApplicationStatus" label="Application Status" model="school.status"/>
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
                        <x-enum-select enum="App\Enum\SchoolPosition" label="Students" model="school.applicant_position"/>
                        <x-input-text label="Applicant's Tel" type="tel" model="school.applicant_phone"/>
                        <x-input-text label="Applicant's Email" type="email" model="school.applicant_email"/>

                        <div class="col-lg-12">
                            <div class="hstack justify-content-end gap-2">
                                <x-button label="Update"/>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@section('script')
    <script src="{{URL::asset('build/js/app.js')}}"></script>
@endsection
