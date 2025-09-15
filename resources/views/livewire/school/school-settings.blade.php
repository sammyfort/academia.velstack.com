@section('title', 'School Settings')
<div class="row" x-init="{ activeTab: 'schoolDetails' }" x-cloak  x-data="{ activeTab: 'schoolDetails' }" >
    <div class="col-xxl-3">
        <div class="card card-bg-fill">
            <div class="card-body p-4">
                <div class="text-center">
                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                        <img src="{{school()->favicon()}}"
                             class="  rounded-circle avatar-xl img-thumbnail user-profile-image"
                             alt="user-profile-image">
                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                            <input wire:model="favicon" id="profile-img-file-input" type="file"
                                   class="profile-img-file-input" wire:change="uploadImages">
                            <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light text-body">
                                        <i class="ri-camera-fill"></i>
                                    </span>
                            </label>
                        </div>
                    </div>
                    <h5 class="fs-16 mb-1">{{school()->name}}</h5>
                    <p class="text-muted mb-0">{{school()->region}}</p>
                </div>
            </div>
        </div>
        <!--end card-->


    </div>
    <!--end col-->
    <div class="col-xxl-9">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                    <li class="nav-item">
                        <a href="javascript:void(0);" @click="activeTab = 'schoolDetails'" :class="{ 'active': activeTab === 'schoolDetails' }" class="nav-link">
                            <i class="fas fa-home"></i> School Details
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0);" @click="activeTab = 'gradingCode'" :class="{ 'active': activeTab === 'gradingCode' }" class="nav-link">
                            <i class="far fa-user"></i> Grading Code
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0);" @click="activeTab = 'notification'" :class="{ 'active': activeTab === 'notification' }" class="nav-link">
                            <i class="far fa-envelope"></i> Notification
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0);" @click="activeTab = 'preferences'" :class="{ 'active': activeTab === 'preferences' }" class="nav-link">
                            <i class="far fa-envelope"></i> Preferences
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body p-4">
                <div class="tab-content">
                    <div x-show="activeTab === 'schoolDetails'" x-cloak>
                        <form wire:submit.prevent="updateSchool">
                            <div class="row">
                                <x-input-text model="school.name" label="Name" />
                                <x-input-text model="school.phone" label="Phone 1" />
                                <x-input-text model="school.phone2" label="Phone 2" />
                                <x-input-text model="school.email" label="Email" />
                                <x-enum-select enum="App\Enum\Region" :choices="false" model="school.region" label="Region" />
                                <x-input-text model="school.district" label="District" />
                                <x-input-text model="school.town" label="Town" />
                                <x-input-text model="school.postal_address" label="Postal Address" />
                                <x-input-text model="school.gps" label="GPS" />
                                <x-input-text model="cover" label="Cover Image" type="file" />
                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-end">
                                        <x-button label="Update" class="btn btn-primary" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--end tab-pane-->
                    <!-- Grading Code Tab -->
                    <div x-show="activeTab === 'gradingCode'" x-cloak>
                        <form wire:submit.prevent="updateGrading">
                            <div class="row g-2">
                                @foreach($grading as $index => $grade)
                                    <div class="row align-items-center mb-3" wire:key="{{ $index }}">
                                        <x-input-text model="grading.{{$index}}.min_score" label="Min Score" wrapper_class="col-md-2" />
                                        <x-input-text model="grading.{{$index}}.max_score" label="Max Score" wrapper_class="col-md-2" />
                                        <x-input-text model="grading.{{$index}}.grade" label="Grade" wrapper_class="col-md-2" />
                                        <x-input-text model="grading.{{$index}}.remark" label="Remark" wrapper_class="col-md-2" />
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm bg-danger mt-2" wire:click.prevent="removeItem({{ $index }})">Remove</button>
                                        </div>
                                    </div>
                                @endforeach
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-success btn-xs" wire:click.prevent="addItem">+ Add Item</button>
                                    </div>
                            </div>
                            @if($grading)
                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-end">
                                        <x-button label="Update" class="btn btn-primary" />
                                    </div>
                                </div>
                            @endif

                        </form>
                    </div>

                    <!--end tab-pane-->
                    <div   x-show="activeTab === 'notification'" x-cloak>

                        <div class="mb-3">
                            <h5 class="card-title text-decoration-underline mb-3">Application Notifications:</h5>
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex">
                                    <div class="flex-grow-1">
                                        <label for="directMessage" class="form-check-label fs-14">
                                            Send Payment Notification </label>
                                        <p class="text-muted">Send confirmation message to parent/payer when a bill is paid</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="form-check form-switch">
                                            <input wire:model.live="alert.send_after_payment" class="form-check-input" type="checkbox" role="switch"
                                                   id="directMessage" checked/>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mt-2">
                                    <div class="flex-grow-1">
                                        <label class="form-check-label fs-14" for="desktopNotification">
                                            Event Notifications
                                        </label>
                                        <p class="text-muted">Send a reminder to event participants a day before the event.</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="form-check form-switch">
                                            <input wire:model.live="alert.send_upcoming_events" class="form-check-input" type="checkbox" role="switch"
                                                   id="desktopNotification" checked/>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mt-2">
                                    <div class="flex-grow-1">
                                        <label class="form-check-label fs-14" for="emailNotification">
                                            Send student attendance alert
                                        </label>
                                        <p class="text-muted"> Send student attendance to parents.
                                            Let them know whether their ward was present or absent. </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="form-check form-switch">
                                            <input wire:model.live="alert.send_student_attendance" class="form-check-input" type="checkbox" role="switch"
                                                   id="emailNotification"/>
                                        </div>
                                    </div>
                                </li>

                                <li class="d-flex mt-2">
                                    <div class="flex-grow-1">
                                        <label class="form-check-label fs-14" for="desktopNotification">
                                           Admission Alert
                                        </label>
                                        <p class="text-muted">Notify parents via SMS after admission.</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="form-check form-switch">
                                            <input wire:model.live="alert.send_admission_alert" class="form-check-input" type="checkbox" role="switch"
                                                   id="desktopNotification" checked/>
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div>
                        <div>
                            <h5 class="card-title text-decoration-underline mb-3">SMS Configurations:</h5>
                            <p class="text-muted">Go to <a href="https://sms.velstack.com">sms.velstack.com</a>
                                to get your API key and sender id and paste the here:
                            </p>
                            <form wire:submit.prevent="saveConfig">
                                <div>
                                    <input wire:model="config.sender_id" type="text" class="form-control mb-3"
                                           placeholder="Sender ID"
                                           style="max-width: 265px;">
                                    <x-error key="config.sender_id"/>

                                    <input wire:model="config.api_key" type="text" class="form-control"
                                           placeholder="API Key"
                                           style="max-width: 265px;">
                                    <x-error key="config.api_key"/>
                                </div>
                                <div class="hstack gap-2 mt-3">
                                    <x-button label="Save"/>

                                </div>
                            </form>

                        </div>

                    <!--end tab-pane-->
                </div>
                    <!--end tab-pane-->

                    <livewire:school.school-preferences/>
                    <!--end tab-pane-->
            </div>
        </div>
    </div>
    <!--end col-->
</div>
<!--end row-->
@section('script')
    <script src="{{ URL::asset('build/js/pages/profile-setting.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
