@section('title', 'Assign Staff')
<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body bg-light-subtle">
                    <div class="d-flex align-items-center">
                        <h6 class="card-title mb-0 flex-grow-1">Search </h6>
                        <div class="flex-shrink-0">

                        </div>
                    </div>

                    <div class="row mt-3 gy-3">
                        <div class="col-xxl-4 col-md-4">
                            <div class="search-box">
                                <input type="text" class="form-control search bg-light border-light" id="searchJob"
                                      wire:model.live="search" autocomplete="off" placeholder="Search ...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>


    @forelse($staff as $worker)
        <div class="row">
            <div class="col-xxl-12">
                <div class="card joblist-card">
                    <div class="card-body">
                        <div class="d-flex mb-0">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-light rounded">
                                    <img src="{{$worker->image()}}" alt="" class="avatar-xxs companyLogo-img">
                                </div>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <img src="{{$worker->image()}}" alt="" class="d-none cover-img">
                                <a href="">
                                    <h5 class="job-title">{{$worker->fullname}}</h5>
                                </a>

                                <div>
                                    <a href="#" class="btn btn-primary float-end"
                                       @click="$dispatch('open-assignment', { staff_id: {{ $worker->id }} })"
                                    >
                                        Assign More <i class="ri-arrow-right-line align-bottom ms-1"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer border-top-dashed">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <spam class="">Classes: </spam>
                                @forelse($worker->assignedClassrooms as $class)
                                    @if(\App\Models\StaffClassroomSubjectPermission::query()
                                          ->where('staff_id', $worker->id)
                                          ->whereNull('subject_id')
                                          ->where('classroom_id', $class->id)
                                          ->exists()
                                         )
                                        <span title="Assigned" class="badge bg-info-subtle text-info d-inline-flex align-items-center">
                                          {{"$class->name"}}
                                              <button type="button" class="btn-close ms-2 text-danger"
                                                      wire:click="removeClassroom({{$worker->id}}, {{$class->id}})"
                                                      aria-label="Close">
                                              </button>
                                        </span>
                                        @endif


                                @empty
                                    <span class="job-location"> No Class Assigned</span>
                                @endforelse
                            </div>
                        </div>
                    </div>


                    <div class="card-footer border-top-dashed">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <spam class="">Teaching Subjects: </spam>
                                @forelse($worker->assignedSubjectsWithClassrooms as $subject)
                                    @foreach($subject->classes as $class)
                                        @if(\App\Models\StaffClassroomSubjectPermission::query()
                                          ->where('staff_id', $worker->id)
                                          ->where('subject_id',  $subject->id)
                                          ->where('classroom_id', $class->id)
                                          ->exists()
                                         )
                                            <span title="Assigned"

                                                  class="badge bg-success-subtle text-success d-inline-flex align-items-center">
                                          {{"$class->name | $subject->name "}}
                                              <button type="button" class="btn-close ms-2 text-danger"
                                                      wire:click="removeSubject({{$worker->id}}, {{$class->id}}, {{$subject->id}})"
                                                      aria-label="Close">
                                              </button>
                                        </span>
                                        @endif
                                    @endforeach

                                @empty
                                    <span class="job-location"> No Subject Assigned</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
    @empty
        <x-no-result/>
    @endforelse

    <x-paginate :collection="$staff"/>

    <!-- Modal -->
    <div x-data="{ openAssignment: false }"
         x-init="openAssignment = false" x-cloak
         @open-assignment.window="openAssignment = true"
         @close-modal.window="openAssignment = false">

        <div class="modal fade"
             aria-hidden="true"
             :class="{ 'show': openAssignment }"
             :style="openAssignment ? 'display: block;' : 'display: none;'"
             tabindex="-1" role="dialog"
             aria-labelledby="openAssignment"
             aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header p-3 ps-4 bg-success-subtle">
                        <h5 class="modal-title" id="inviteMembersModalLabel">Assign Staff To Class</h5>
                        <button type="button" class="btn-close" @click="openAssignment = false" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="assign">
                        <div class="modal-body p-4">

                            <div class="col-lg-12">
                                <div class="text-center">
                                    <div class="position-relative d-inline-block">

                                        <div class="avatar-lg p-1">
                                            <div class="avatar-title bg-light rounded-circle">
                                                <img src="{{$assignee?->image()}}"
                                                     alt="" id="lead-img"
                                                     class="avatar-md rounded-circle object-fit-cover">
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="fs-13 mt-3">{{$assignee?->fullname}}</h5>
                                </div>
                            </div>

                            <div class="search-box mb-3">
                                <input wire:model.live="permissionSearch" type="text"
                                       class="form-control bg-light border-light" placeholder="Search here...">
                                <i class="ri-search-line search-icon"></i>
                            </div>

                            <x-input-select model="assignment.class_id" label="Select Class" :options="$classes"
                                            key="id"
                                            bind=".live"
                                            value="name" wrapper_class="col-md-12"/>

                            <x-enum-select model="assignment.role_name" label="Select Role" enum="App\Enum\ClassRole"
                                           bind=".live" value="name" wrapper_class="col-md-12"/>


                            @if($assignment['class_id'] && $assignment['role_name'] == \App\Enum\ClassRole::SUBJECT_TEACHER->value)
                                <x-input-select label="Select Subject" :options="$class_subjects" key="id"
                                                bind=".live" model="assignment.subject_id"
                                                value="name" wrapper_class="col-md-12"/>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <x-button label="Assign"/>

                        </div>
                    </form>
                </div>
                <!-- end modal-content -->
            </div>
            <!-- modal-dialog -->
        </div>
        <!-- Backdrop -->
        <div x-show="openAssignment" class="modal-backdrop fade show" style="z-index: 1040;"></div>
    </div>

    <!-- end modal -->


</div>
@section('script')

    <!-- App js -->
    <script src="{{URL::asset('build/js/app.js')}}"></script>
@endsection
