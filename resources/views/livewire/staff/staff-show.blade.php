@section('title', 'Staff Details')
<div>
    <div class="row"  >
        <div class="col-lg-12">
            <div class="card mt-n4 mx-n4 card-border-effect-none">
                <div class="bg-primary-subtle">
                    <div class="card-body pb-0 px-4">
                        <div class="row mb-3">
                            <div class="col-md">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-auto">
                                        <div class="avatar-md">
                                            <div class="avatar-title bg-white rounded-circle">
                                                <img src="{{$staff->image()}}" alt=""
                                                     class="avatar-title bg-white rounded">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div>
                                            <h4 class="fw-bold">{{$staff->fullname}}</h4>
                                            <div class="hstack gap-3 flex-wrap mb-2">
                                                <div class=" rounded-pill fs-12 text-success"> {{$staff->designation}}</div>
                                            </div>
                                            <div class="hstack gap-3 flex-wrap">

                                                <div class=" rounded-pill fs-12"><i
                                                        class="ri-calendar-2-fill align-bottom me-1">
                                                    </i> Joined: {{$staff->created_at->format('d F, Y')}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mt-4">
                                            <div>
                                                <a href="{{route('timetables.staff.print', ['uuid' =>$staff->uuid, 'term_uuid' => currentTerm()->uuid])}}" target="_blank" class="btn btn-warning" >
                                                    <i class="ri-printer-fill align-bottom me-1 me-2"></i>
                                                    Print Timetable
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <!-- end card body -->
                </div>
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content text-muted">
                <div class="tab-pane fade show active" id="project-overview" role="tabpanel">
                    <div class="row">
                        <div class="col-xl-9 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text">
                                        <h6 class="mb-3 fw-semibold text-uppercase">Summary</h6>
                                        <p>{{$staff->bio}}</p>

                                        <div class="pt-3 border-top border-top-dashed mt-4">
                                            <div class="row gy-3">
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium">Staff ID:</p>
                                                        <h5 class="fs-15 mb-0 text-info">{{$staff->staff_id}}</h5>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium">Licence No:</p>
                                                        <h5 class="fs-15 mb-0 text-info">{{$staff->licence_no}}</h5>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium">Basic Salary :</p>
                                                        <h5 class="fs-15 mb-0 text-info">{{$staff->basic_salary}}</h5>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium">Gender:</p>
                                                        <h5 class="fs-15 mb-0 text-info">{{$staff->gender}}</h5>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium">Phone Number :</p>
                                                        <h5 class="fs-15 mb-0 text-info">{{$staff->phone}}</h5>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium">Email address :</p>
                                                        <h5 class="fs-15 mb-0 text-info"><a class="text-info"
                                                                                            href="mailto:{{$staff->email}}">{{$staff->email}}</a>
                                                        </h5>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium">Ghana Card :</p>
                                                        <h5 class="fs-15 mb-0 text-info">{{$staff->national_id}}</h5>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium">Date of Birth:</p>
                                                        <h5 class="fs-15 mb-0 text-info">{{$staff->dob}}</h5>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium">Religion:</p>
                                                        <h5 class="fs-15 mb-0 text-info">{{$staff->religion}}</h5>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium">Region:</p>
                                                        <h5 class="fs-15 mb-0 text-info">{{$staff->region}}</h5>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium">Hometown:</p>
                                                        <h5 class="fs-15 mb-0 text-info">{{$staff->city}}</h5>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium">Marital Status:</p>
                                                        <h5 class="fs-15 mb-0 text-info">{{$staff->marital_status}}</h5>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium">Qualification :</p>
                                                        <div
                                                            class="badge bg-success fs-12">{{str($staff->qualification)->upper()}}</div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium">Experience :</p>
                                                        <div
                                                            class=" text-info fs-15">{{str($staff->experience)->headline()}}</div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium">Bank Name :</p>
                                                        <h5 class="fs-15 mb-0 text-info">{{$staff->bank->name}}</h5>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium">Branch :</p>
                                                        <h5 class="fs-15 mb-0 text-info">{{$staff->bank->branch}}</h5>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium">Account Number :</p>
                                                        <h5 class="fs-15 mb-0 text-info">{{$staff->bank->account_number}}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if($staff->appointment_letter || $staff->certificate_image)
                                            <div class="pt-3 border-top border-top-dashed mt-4">
                                                <h6 class="mb-3 fw-semibold text-uppercase">Resources</h6>
                                                <div class="row g-3">
                                                    @if($staff->appointment_letter)
                                                        <div class="col-xxl-4 col-lg-6">
                                                            <x-file-viewer label="Appointment Letter"
                                                                           route="{{route('staff.appointment.letter', $staff->uuid)}}"
                                                            />
                                                        </div>
                                                    @endif

                                                    @if($staff->certificate_image)
                                                        <div class="col-xxl-4 col-lg-6">
                                                            <x-file-viewer label="Certificate"
                                                                           route="{{route('staff.certificate', $staff->uuid)}}"
                                                            />
                                                        </div>
                                                    @endif
                                                    <!-- end col -->
                                                </div>
                                                <!-- end row -->
                                            </div>
                                        @endif

                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Activities</h5>
                                    <div class="acitivity-timeline py-3">
                                        @forelse($logs as $log)
                                            @php
                                                $bg = match ($log->event){
                                                   'deleted' => 'danger',
                                                   'created' => 'success',
                                                   'updated' => 'info',
                                                   'restored' => 'primary',
                                                   default  => 'secondary'
                                                 };
                                            @endphp
                                            <div class="acitivity-item py-3 d-flex">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-xs acitivity-avatar">
                                                        <div
                                                            class="avatar-title rounded-circle bg-{{$bg}}-subtle text-{{$bg}}">
                                                            <i class="ri-history-line"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">{{($log->subject->logger_name ?? str_replace('_', '', class_basename($log->subject_type))). " $log->event by {$log->causer->fullname}"}}

                                                        <span
                                                            class="badge bg-{{$bg}}-subtle text-{{$bg}} align-middle ms-1">{{$log->event}}</span>
                                                    </h6>
                                                    <p class="text-muted mb-2"> {!! $log->description !!} </p>

                                                    <small class="mb-0 text-muted">
                                                        @if ($log->created_at->diffInHours() < 24)
                                                            {{ $log->created_at->diffForHumans() }}
                                                        @else
                                                            {{ $log->created_at->format('d M Y - h:i A') }}
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>

                                        @empty
                                            <p class="text-muted mb-2">No Log Found</p>
                                        @endforelse


                                        <x-paginate :collection="$logs"/>
                                    </div>
                                </div>
                                <!--end card-body-->
                            </div>

                        </div>



                        <!-- ene col -->
                        <div class="col-xl-3 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Classes</h5>
                                    <div class="d-flex flex-wrap gap-2 fs-16">
                                        @forelse($staff->assignedClassrooms as $class)
                                            @if(\App\Models\StaffClassroomSubjectPermission::query()
                                                  ->where('staff_id', $staff->id)
                                                  ->whereNull('subject_id')
                                                  ->where('classroom_id', $class->id)
                                                  ->exists()
                                                 )
                                                <span title="Assigned"
                                                      class="badge bg-info-subtle text-info d-inline-flex align-items-center">
                                          {{"$class->name"}}
                                              <button type="button" class="btn-close ms-2 text-danger"
                                                      wire:click="removeClassroom({{$staff->id}}, {{$class->id}})"
                                                      aria-label="Close">
                                              </button>
                                        </span>
                                            @endif

                                        @empty
                                            <span class="text-warning"> No Class Assigned</span>
                                        @endforelse
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                            <div class="card">

                                <div class="card-body">
                                    <h5 class="card-title mb-4">Subjects</h5>

                                    <div class="d-flex flex-wrap gap-2 fs-16">
                                        @forelse($staff->assignedSubjectsWithClassrooms as $subject)
                                            @foreach($subject->classes as $class)
                                                @if(\App\Models\StaffClassroomSubjectPermission::query()
                                                  ->where('staff_id', $staff->id)
                                                  ->where('subject_id',  $subject->id)
                                                  ->where('classroom_id', $class->id)
                                                  ->exists()
                                                 )
                                                    <span title="Assigned"
                                                          class="badge bg-success-subtle text-success d-inline-flex align-items-center">{{"$class->name | $subject->name "}}
                                                     <button type="button" class="btn-close ms-2 text-danger"
                                                             wire:click="removeSubject({{$staff->id}}, {{$class->id}}, {{$subject->id}})"
                                                             aria-label="Close"></button>
                                                  </span>

                                                @endif
                                            @endforeach

                                        @empty
                                            <span class="text-warning"> No Subject Assigned</span>
                                        @endforelse
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">System Roles</h5>
                                    <div class="d-flex flex-wrap gap-2 fs-16">
                                        @forelse($staff->roles as $role)
                                            <div
                                                class="badge fw-medium bg-success-subtle text-success">{{$role->name}}</div>
                                        @empty
                                            <div class="badge fw-medium bg-danger-subtle text-danger">Has No
                                                Role
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->


                        </div>
                        <!-- end col -->

                    </div>
                    <!-- end row -->
                </div>
                <!-- end tab pane -->


            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->


</div>
@section('script')
    <script src="{{ URL::asset('build/js/pages/project-overview.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
