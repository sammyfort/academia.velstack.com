
@section('title', "Update Staff")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="update">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Update Staff</h5>
                    <div class="d-flex gap-2">
                        <x-link label="Staff" route="staff.index" class="btn btn-info add-bt" icon="ri-grid-fill me-1 align-bottom"/>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <x-enum-select enum="App\Enum\StaffType" label="Designation" model="staff.designation" wrapper_class="col-md-4"/>
                        <x-input-text label="License Number" model="staff.licence_no" wrapper_class="col-md-4"/>
                        <x-input-text label="Staff ID " model="staff.staff_id" wrapper_class="col-md-4"/>
                        <x-enum-select enum="App\Enum\Title" label="Title" model="staff.title" wrapper_class="col-md-4"/>
                        <x-input-text label="First Name" model="staff.first_name" wrapper_class="col-md-4"/>
                        <x-input-text label="Middle Name" model="staff.middle_name" wrapper_class="col-md-4"/>
                        <x-input-text label="Last Name" model="staff.last_name" wrapper_class="col-md-4"/>
                        <x-input-text label="Email address" type="email" model="staff.email" wrapper_class="col-md-4"/>
                        <x-input-text label="Phone Number" type="tel" model="staff.phone" wrapper_class="col-md-4"/>
                        <x-input-text label="Ghana Card No"  model="staff.national_id" wrapper_class="col-md-4"/>
                        <x-enum-select enum="App\Enum\Gender" label="Gender" model="staff.gender" wrapper_class="col-md-4"/>
                        <x-enum-select enum="App\Enum\MaritalStatus" label="Marital Status" model="staff.marital_status" wrapper_class="col-md-4"/>
                        <x-input-text label="Date of birth" model="staff.dob" type="date" wrapper_class="col-md-4"/>
                        <x-enum-select  enum="App\Enum\Region" label="Region" model="staff.region" wrapper_class="col-md-4"/>
                        <x-input-text label="Town/City" model="staff.city" wrapper_class="col-md-4"/>
                        <x-enum-select  enum="App\Enum\Religion" label="Religion" model="staff.religion" wrapper_class="col-md-4"/>
                        <x-input-text wrapper_class="col-md-4"  label="Upload Photo (150px X 150px)" type="file" model="profileImage" info>
                            @if($profileImage)
                                <div class="upload-images mt-3">
                                    <img src="{{$profileImage->temporaryUrl()}}" style="height: 100px; width: 100px" alt="Image">
                                    <a href="javascript:void(0);" class="btn-icon logo-hide-btn">
                                        <i class="feather-x-circle"></i>
                                    </a>
                                </div>
                            @else
                                <div class="upload-images mt-3">
                                    <img src="{{$staffWorker->image()}}" style="height: 100px; width: 100px" alt="Image">
                                    <a href="javascript:void(0);" class="btn-icon logo-hide-btn">
                                        <i class="feather-x-circle"></i>
                                    </a>
                                </div>
                            @endif
                        </x-input-text>
                        <x-input-text label="Basic Salary" type="number"  model="staff.basic_salary" wrapper_class="col-md-4"/>
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Bank Details</h5>
                        </div>
                        <x-input-text label="Bank Name"   model="bank.name" wrapper_class="col-md-4"/>
                        <x-input-text label="Branch"   model="bank.branch" wrapper_class="col-md-4"/>
                        <x-input-text label="Account Number"   model="bank.account_number" wrapper_class="col-md-4"/>

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Educational Background</h5>
                        </div>
                        <x-enum-select  enum="App\Enum\StaffQualification" label="Qualification" model="staff.qualification" wrapper_class="col-md-4"/>
                        <x-enum-select  enum="App\Enum\StaffExperience" label="Experience" model="staff.experience" wrapper_class="col-md-4"/>

                        <x-input-text wrapper_class="col-md-4" label="Upload Certificate (PDF)" type="file" model="certificateImage" info>
                            @if($certificateImage)
                                <div class="upload-images mt-3">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <x-doc-file height="70" width="70"/>
                                        <span>Uploaded File:  <span class="text-success">{{$certificateImage->getClientOriginalName()}}</span></span>
                                    </div>
                                </div>
                            @endif
                        </x-input-text>

                        <x-input-text wrapper_class="col-md-4" label="Appointment Letter (PDF)" type="file" model="appointmentLetter" info>
                            @if($appointmentLetter)
                                <div class="upload-images mt-3">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <x-doc-file height="70" width="70"/>
                                        <span>Uploaded File:  <span class="text-success">{{$appointmentLetter->getClientOriginalName()}}</span></span>
                                    </div>
                                </div>
                            @endif
                        </x-input-text>



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
