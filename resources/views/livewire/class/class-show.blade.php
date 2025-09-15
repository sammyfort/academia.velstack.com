@section("title")
    {{"$class->name"}}
@endsection


<div x-init="
        activeTab = localStorage.getItem('activeTab') || 'all';
        $watch('activeTab', value => localStorage.setItem('activeTab', value));
    "
     x-cloak
     x-data="{ activeTab: 'all' }">
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="orderList">
                <div class="card-header border-0">
                    <div class="row align-items-center gy-3">
                        <div class="col-sm">
                            <h5 class="card-title mb-2">{{$class->name}}</h5>
                            <h5 class="text-muted mb-0">{{$class->students->count()}} Students</h5>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex gap-1 flex-wrap">
                                @if($class->students->count() > 0)
                                    <button wire:click="exportStudents" class="btn btn-success add-btn">
                                        <i class="ri-file-excel-2-fill align-bottom me-2"></i> Export Students
                                    </button>
                                @endif
                                <a href="{{route('timetables.class.print', ['uuid' => $class->uuid, 'term_uuid' => currentTerm()->uuid])}}"
                                   target="_blank" class="btn btn-warning add-btn">
                                    <i class="ri-clockwise-line align-bottom me-2"></i> Timetable
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $isClassTeacher = false;
                    if(permittedTo($class, \App\Enum\ClassRole::CLASS_TEACHER->value)){
                        $isClassTeacher = true;
                    }

                @endphp

                <ul class="nav nav-tabs nav-tabs-custom nav-primary mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link All py-3" :class="{ 'active': activeTab === 'all' }"
                           data-bs-toggle="tab" id="All"
                           @click.prevent="activeTab = 'all'"
                           role="tab" aria-selected="true">
                            <i class="ri-store-2-fill me-1 align-bottom"></i> All Students
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link py-3" :class="{ 'active': activeTab === 'academics' }"
                           data-bs-toggle="tab" id="ranking"
                           @click.prevent="activeTab = 'academics'"
                           role="tab" aria-selected="false">
                            <i class="ri-truck-line me-1 align-bottom"></i> Academics
                        </a>
                    </li>

                    @if($isClassTeacher)
                        <li class="nav-item">
                            <a class="nav-link py-3" :class="{ 'active': activeTab === 'attendance' }"
                               data-bs-toggle="tab" id="Delivered"
                               @click.prevent="activeTab = 'attendance'"
                               role="tab" aria-selected="false">
                                <i class="ri-checkbox-circle-line me-1 align-bottom"></i> Attendance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3" :class="{ 'active': activeTab === 'scoreType' }"
                               data-bs-toggle="tab" id="scoreType"
                               @click.prevent="activeTab = 'scoreType'"
                               role="tab" aria-selected="false">
                                <i class="ri-truck-line me-1 align-bottom"></i> Score Types
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link py-3" :class="{ 'active': activeTab === 'subjects' }"
                               data-bs-toggle="tab" id="subjects"
                               @click.prevent="activeTab = 'subjects'"
                               role="tab" aria-selected="false">
                                <i class="ri-arrow-left-right-fill me-1 align-bottom"></i> Subjects
                                <span class="badge bg-info align-middle ms-1">{{$class->subjects->count()}}</span>
                            </a>
                        </li>
                    @endif
                </ul>

                <div>
                    <div>
                        <div class="card-body border border-dashed border-end-0 border-start-0 mb-4">
                            <form>
                                <div class="row g-3">
                                    <div class="col-xl-12">
                                        <div class="d-flex flex-wrap align-items-center gap-3">
                                            <!-- Search Box -->
                                            <div class="flex-shrink-0" style="width: 200px;">
                                                <div class="search-box">
                                                    <input wire:model.live="search" type="text"
                                                           class="form-control search"
                                                           placeholder="Search something...">
                                                    <i class="ri-search-line search-icon"></i>
                                                </div>
                                            </div>

                                            <!-- Filters Button -->
                                            <div>
                                                <button type="button" class="btn btn-primary"
                                                        wire:click.prevent="resetFilter()">
                                                    <i class="ri-equalizer-fill me-2 align-bottom"></i>Filters
                                                </button>
                                            </div>

                                            <x-input-text x-show="activeTab === 'attendance'"
                                                wire:model.live="date" placeholder="Admission Date"
                                                type="date" id="date" wrapper_class="mt-3"/>


                                            <div>
                                                <x-input-select model="term_id"
                                                                bind=".live" :options="$terms"
                                                                key="id" value="name"
                                                                placeholder="Select Term" wrapper_class="mt-3"/>
                                            </div>

                                            @if($isClassTeacher)
                                                <!-- Dropdown -->
                                                <div x-show="activeTab === 'academics'">
                                                    @if(count($selectedStudents) > 0)
                                                        <div class="btn-group me-2">
                                                            <button type="button"
                                                                    class="btn btn-warning dropdown-toggle"
                                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                With Select ({{count($selectedStudents)}})
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item"
                                                                   href="#" @click="$dispatch('promote-students')">Promote
                                                                    Students
                                                                </a>
                                                                <div class="dropdown-divider"></div>
                                                                <a
                                                                    class="dropdown-item" href="#"
                                                                    @click="$dispatch('mark-completed')">Mark Completed
                                                                </a>
                                                                <div class="dropdown-divider"></div>

                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if($term_id)
                                                        <a type="button" class="btn btn-warning"
                                                           href="{{route('classes.class.terminal.report', ['term_id' => $term_id, 'class_id' => $class->id])}}"
                                                           target="_blank">
                                                            <i class="ri-printer-cloud-line me-2 align-bottom"></i>Terminal
                                                            Report
                                                        </a>
                                                    @endif
                                                </div>
                                                <div x-show="activeTab === 'scoreType'">

                                                    <x-link label="Add ScoreType" route="scoretype.create"
                                                            :param="$class->uuid"
                                                            class="btn btn-primary me-2"
                                                            icon="ri-add-fill align-bottom"/>

                                                    <button type="button" class="btn btn-primary createTask"
                                                            @click="$dispatch('open-attach')">
                                                        <i class="ri-add-fill align-bottom"></i> Attach ScoreType
                                                    </button>
                                                </div>

                                                <!-- Sync Subjects -->
                                                <div x-show="activeTab === 'subjects'">
                                                    <button type="button" class="btn btn-primary"
                                                            wire:click.prevent="syncSubjects()">
                                                        <i class="ri-equalizer-fill me-2 align-bottom"></i>Sync Subjects
                                                    </button>
                                                </div>

                                            @endif

                                        </div>

                                    </div>
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                        <div class="card-body pt-0">

                            <div x-show="activeTab === 'all'" class="table-responsive table-card mb-1">

                                <table class="table table-nowrap align-middle table-hover" id="home1">
                                    <thead class="text-muted table-light">
                                    <tr class="text-uppercase">
                                        <th>#</th>
                                        <th>Index Number</th>
                                        <th>Full Name</th>
                                        <th>Subjects Studying</th>
                                        <th>Subjects Name</th>
                                        <th>Admission Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                    @forelse($students as $student)
                                        <tr  class="clickable-row" data-href="{{ route('students.show', $student->uuid) }}">
                                            <td class="customer_name">{{$loop->iteration}}</td>
                                            <td class="id">{{$student->index_number}}</td>
                                            <td class="customer_name">{{$student->fullname}}</td>
                                            <td class="customer_name">{{$student->subjects->count()}}</td>
                                            <td class="subjects">

                                                <div class="d-flex flex-wrap gap-1">
                                                    @forelse($class->subjects as $subject)
                                                        @if(in_array($subject->id, $student->subjects->pluck('id')->toArray()))
                                                            <span title="Assigned" class="badge bg-success-subtle text-success">{{ $subject->name }}</span>
                                                        @else
                                                            <span title="Not Assigned" class="badge bg-danger-subtle text-danger">{{ $subject->name }}</span>
                                                        @endif
                                                    @empty
                                                        <span class="text-danger">No Subject(s)</span>
                                                    @endforelse
                                                </div>

                                            </td>
                                            <td class="date">{{$student->created_at->format('d F, Y')}}</td>
                                            <td>
                                                <x-table-actions :id="$student->uuid">

                                                    @if($isClassTeacher)
                                                        <li class="list-inline-item non-clickable" title="Subjects">
                                                            <x-link route="students.subject" param="{{$student->uuid}}"
                                                                    class="text-primary d-inline-block"
                                                                    icon="ri-book-open-fill fs-24"/>
                                                        </li>
                                                    @endif

                                                </x-table-actions>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center">
                                                <x-no-result description="No Student found"/>
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                                <x-paginate :collection="$students"/>
                            </div>

                            <div x-show="activeTab === 'attendance'">
                                @if(!$studentModel && collect($attendances)->isEmpty())
                                    <div class="card-body pt-0">
                                        <div>
                                            <div class="table-responsive table-card mb-1">

                                                <table class="table table-nowrap align-middle table-hover" id="home1">
                                                    <thead class="text-muted table-light">
                                                    <tr class="text-uppercase">
                                                        @php
                                                            $date = \Carbon\Carbon::parse($this->date);
                                                        @endphp

                                                        <th data-sort="id">#</th>
                                                        <th data-sort="product_name">Index Number</th>
                                                        <th data-sort="customer_name">Full Name</th>
                                                        <th data-sort="date">Term Total ({{$term?->name}})</th>
                                                        <th data-sort="date">Date @if($date)
                                                                - ({{$date->format('d M, y')}})
                                                            @endif</th>
                                                        <th data-sort="city">Mark</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="list form-check-all">
                                                    @if($date and $term)
                                                        @forelse($students as $student)
                                                            <tr style="cursor: pointer;"
                                                                wire:click="openTimeSheet({{ $student->id }})">
                                                                <td class="customer_name">{{$loop->iteration}}</td>
                                                                <td class="id">{{$student->index_number}}</td>
                                                                <td class="customer_name">{{$student->fullname}}</td>
                                                                <td class="customer_name">
                                                                    @php
                                                                        $count = $student->attendances->where('term_id', $term_id)->where('present', true)->count();
                                                                    @endphp
                                                                    <span
                                                                        class="text-{{$count >= $term->days ? 'success' : 'danger'}}">{{$count}}</span>/<span
                                                                        class="text-success">{{$term->days}}</span>

                                                                </td>
                                                                <td class="text-dark">
                                                                    @php
                                                                        $attendance = $student->attendances->where('term_id', $term_id)->where('date', $date)->first();
                                                                    @endphp
                                                                    @if($attendance)
                                                                        <span title="Not Assigned"
                                                                              class="badge bg-{{$attendance->present ? 'success': 'danger'}}-subtle text-{{$attendance->present ? 'success': 'danger'}}">
                                                            {{ $attendance->present ? 'Present' : 'Absent'}}
                                                        </span>
                                                                    @else
                                                                        <span title="Assigned" class="text-danger">No Record</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <ul class="list-inline hstack gap-2 mb-0">
                                                                        <li class="list-inline-item"
                                                                            title="Enter Results">
                                                                            <button type="button"
                                                                                    class="btn btn-success btn-sm"
                                                                                    onclick="event.stopPropagation()"
                                                                                    wire:click.prevent="recordAttendance({{ $student->id }}, 1)">
                                                                                Present
                                                                            </button>
                                                                        </li>
                                                                        <li class="list-inline-item"
                                                                            title="Assign ScoreType">
                                                                            <button type="button"
                                                                                    class="btn btn-danger btn-sm"
                                                                                    onclick="event.stopPropagation()"
                                                                                    wire:click.prevent="recordAttendance({{ $student->id }}, 0)">
                                                                                Absent
                                                                            </button>

                                                                        </li>
                                                                    </ul>
                                                                </td>

                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="12" class="text-center">
                                                                    <x-no-result description="No Student found"/>
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    @else
                                                        <tr>
                                                            <td colspan="12" class="text-center">
                                                                <x-no-result title="No Attendance"
                                                                             description="Please select date and term"/>
                                                            </td>

                                                        </tr>

                                                    @endif

                                                    </tbody>
                                                </table>
                                                <x-paginate :collection="$students"/>

                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="card-body">
                                        <div class=" mb-2">
                                            <button type="button" class="btn btn-danger text-center float-end"
                                                    wire:click.prevent="closeTimeSheet()">
                                                <i class="ri-close me-2 align-bottom"></i>Close Attendance Sheet
                                            </button>
                                        </div>
                                        <h3 class="text-center">Attendance Report for {{$studentModel->fullname}}
                                            ({{$term->name}}) </h3>

                                        <table class="table table-bordered mt-4">
                                            <thead class="table-dark">
                                            <tr>
                                                <th>Date</th>
                                                <th>Attendance</th>
                                                <th>Recorded By</th>
                                                <th>Recorded Time</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($attendances as $attendance)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('D M d, Y') }}</td>
                                                    <td class="text-center">
                                                        {!! $attendance->present ? '<span class="text-success">✔️</span>' : '<span class="text-danger">❌</span>' !!}
                                                    </td>
                                                    <td class="text-center">{{ $attendance->createdBy->fullname }}</td>

                                                    <td class="text-center">{{$attendance->created_at->format('d M, Y')}}
                                                        <small
                                                            class="text-muted">{{$attendance->created_at->format('h:i A')}}</small>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>

                            <div x-show="activeTab === 'academics'" class="table-responsive table-card mb-1">
                                <div x-data="{ showDropdown: false }"
                                     @click.away="showDropdown = false">
                                    <table class="table table-nowrap align-middle table-hover" id="home1">
                                        <thead class="text-muted table-light">
                                        <tr class="text-uppercase">
                                            <th scope="col">
                                                <input
                                                    type="checkbox"
                                                    wire:model.live="allSelected"
                                                    @click="showDropdown = $event.target.checked"
                                                />
                                            </th>
                                            <th scope="col">#</th>
                                            <th scope="col">Student Name</th>
                                            <th scope="col">Subjects Recorded</th>
                                            <th scope="col">Total Score</th>
                                            <th scope="col">Rank</th>
                                            <th scope="col">Formaster Remark</th>
                                            <th scope="col">Headmaster Remark</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                        @forelse($rankings as $ranking)
                                            @php
                                                $subjectCount = $ranking['scores']->pluck('subject_id')->unique()->count();
                                                $subjectsRecorded = $ranking['scores']->pluck('subject.name')->unique();
                                            @endphp
                                            <tr  class="clickable-row" data-href="{{ route('students.show', $ranking['student']->uuid) }}">
                                                <td class=non-clickable>
                                                    <input
                                                        type="checkbox"
                                                        wire:model.live="selectedStudents"
                                                        value="{{ $ranking['student']->id }}"
                                                        @change="showDropdown = $wire.selectedStudents.length > 0"
                                                    />
                                                </td>
                                                <td class="customer_name">{{$loop->iteration}}</td>
                                                <td>{{ $ranking['student']->fullname }}</td>
                                                <td>
                                                    {{ $subjectCount }}
{{--                                                    @forelse($subjectsRecorded as $sub)--}}
{{--                                                        <span title="Assigned" class="badge bg-success-subtle--}}
{{--                                                  text-success d-inline-flex align-items-center"> ( {{ $sub}})</span>--}}
{{--                                                    @empty--}}

{{--                                                    @endforelse--}}
                                                </td>
                                                <td>{{ $ranking['total_score'] }}

                                                <td>{{ $ranking['rank'] }}

                                                @php
                                                    $remark =  $ranking['student']->currentTermRemark ?? null;
                                                @endphp
                                                    <td>{{$remark->attitude ?? 'NA'}} | {{$remark->interest ?? 'NA'}}</td>
                                                    <td>{{$remark->conduct ?? 'NA'}} | {{$remark->remark ?? 'NA'}}</td>



                                                <td>
                                                    <ul class="list-inline hstack gap-2 mb-0">
                                                        <x-table-actions>
                                                            <li class="list-inline-item non-clickable" title="Enter Results">
                                                                <a class="btn btn-primary btn-sm d-inline-block"
                                                                   @click="$dispatch('enter-result', { id: {{ $ranking['student']->id }} })"
                                                                >Enter Scores</a>
                                                            </li>

                                                            <li class="list-inline-item non-clickable" title="Enter Remarks">
                                                                <a class="btn btn-info btn-sm d-inline-block"
                                                                   @click="$dispatch('open-remark', { id: {{ $ranking['student']->id }} })"
                                                                >Remarks</a>
                                                            </li>
                                                        </x-table-actions>

                                                        <li class="list-inline-item non-clickable" title="Assign ScoreType">
                                                            <a href="{{route('terminal.report', ['uuid' => $ranking['student']->uuid, 'term_id' => $term_id, 'class_id' => $class->id])}}"
                                                               target="_blank" class="btn btn-warning btn-sm"
                                                               title="Generate report">
                                                                TERMINAL REPORT
                                                            </a>
                                                        </li>

                                                    </ul>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="12" class="text-center">
                                                    <x-no-result description="No Ranking found"/>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    <x-paginate :collection="$students"/>
                                </div>

                            </div>


                            <div x-show="activeTab === 'scoreType'" class="table-responsive table-card mb-1">
                                <div x-show="activeTab === 'scoreType'">
                                    <table class="table table-nowrap align-middle" id="home1">
                                        <thead class="text-muted table-light">
                                        <tr class="text-uppercase">

                                            <th>#</th>
                                            <th>Score Type</th>
                                            <th>Percentage (%)</th>

                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                        @forelse($class->scoreTypes as $scoreType)
                                            <tr>
                                                <td class="customer_name">{{$loop->iteration}}</td>
                                                <td class="id"><a href=""
                                                                  class="fw-medium link-success">{{$scoreType->name}}</a>
                                                </td>
                                                <td class="customer_name">{{$scoreType->percentage}}</td>

                                                <td>
                                                    <x-table-actions>
                                                        <li class="list-inline-item" title="Remove">
                                                            <a href="{{ route('scoretype.edit', ['class_uuid' => $class->uuid, 'scoretype_uuid' => $scoreType->uuid]) }}"
                                                               class="text-primary d-inline-block remove-item-btn">
                                                                <i class="ri-pencil-fill fs-24"></i>
                                                            </a>

                                                        </li>

                                                        <li class="list-inline-item" title="Remove">
                                                            <a class="text-danger d-inline-block remove-item-btn"
                                                               @click="$dispatch('open-delete-modal', {model: 'ScoreType',  modelId:{{ $scoreType->id }},recordName: 'ScoreType' })">
                                                                <i class="ri-delete-bin-5-fill fs-24"></i>
                                                            </a>
                                                        </li>

                                                    </x-table-actions>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="12" class="text-center">
                                                    <x-no-result description="No score type found"/>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    {{--                <x-paginate :collection="$students"/>--}}
                                </div>
                            </div>

                            <div x-show="activeTab === 'subjects'" class="table-responsive table-card mb-1">
                                <div x-show="activeTab === 'subjects'">
                                    <table class="table table-nowrap align-middle" id="home1">
                                        <thead class="text-muted table-light">
                                        <tr class="text-uppercase">

                                            <th data-sort="id">#</th>
                                            <th data-sort="customer_name">Subject Name</th>
                                            <th data-sort="product_name">Subject Code</th>
                                            <th data-sort="product_name">Score Types</th>
                                            <th data-sort="date">Students Studying</th>

                                        </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                        @forelse($class->subjects as $subject)
                                            <tr>
                                                <td class="customer_name">{{$loop->iteration}}</td>
                                                <td class="id"><a href=""
                                                                  class="fw-medium link-success">{{$subject->name}}</a>
                                                </td>
                                                <td class="customer_name">{{$subject->code}}</td>
                                                <td>
                                                    @forelse($subject->scoreTypes->where('class_id', $class->id) as $score)
                                                        <span title="Assigned" class="badge bg-success-subtle
                                                    text-success d-inline-flex align-items-center">{{ $score->name }}
                                                        <button type="button" class="btn-close ms-2 text-danger"
                                                                aria-label="Close"
                                                                wire:click="removeScoreType({{$subject->id}}, {{$score->id}})">
                                                        </button>
                                                    </span>
                                                    @empty
                                                        <span class="text-danger">No Score Type</span>
                                                    @endforelse
                                                </td>
                                                <td class="count">{{$subject->students->pluck('id')->unique()->count()}}</td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="12" class="text-center">
                                                    <x-no-result description="No Class Subject found"/>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    {{--                <x-paginate :collection="$students"/>--}}
                                </div>
                            </div>

                        </div>
                    </div>

                </div>


            </div>
        </div>
        <!--end col-->
    </div>
    <livewire:modal.delete-modal/>

    {{--    MODALS--}}
    {{--    enter scores--}}
    <div x-data="{ showResult: false }"
         x-init="showResult = false" x-cloak
         @enter-result.window="showResult = true"
         @close-modal.window="showResult = false">

        <!-- Modal -->
        <div class="modal fade"
             :class="{ 'show': showResult }"
             :style="showResult ? 'display: block;' : 'display: none;'"
             tabindex="-1" role="dialog"
             aria-labelledby="showResult"
             aria-hidden="true">


            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-primary-subtle p-3">
                        <h5 class="modal-title" id="exampleModalLabel">Academic Results <span class="text-success">({{$term?->name}})</span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                @click="showResult = false" wire:click="$dispatch('close-result')"
                                id="close-modal"></button>
                    </div>
                    <form wire:submit.prevent="saveScore">
                        <div class="modal-body">
                            <input type="hidden" id="id-field"/>
                            @if($studentForScore)
                                <div class="row g-3">
                                    <div class="col-lg-12">
                                        <div class="text-center">
                                            <div class="position-relative d-inline-block">
                                                <div class="avatar-lg p-1">
                                                    <div class="avatar-title bg-light rounded-circle">
                                                        <img src="{{ $studentForScore->image()}}"
                                                             alt="" id="student image"
                                                             class="avatar-md rounded-circle object-fit-cover">
                                                    </div>
                                                </div>
                                            </div>
                                            <h5 class="fs-13 mt-3">{{$studentForScore->fullname}}</h5>
                                        </div>
                                        <x-input-select wrapper_class="col-md-12" label="Select Subject"
                                                        model="subject_id" bind=".live"
                                                        key="id" value="name"
                                                        :options="$studentSubjects"
                                        />
                                    </div>

                                    @if($subject_id)
                                        @forelse($scoreTypes as $scoreType)
                                            <div class="col-lg-6 col-md-6"
                                                 wire:key="score-{{ $subject_id }}-{{ $scoreType->id }}">
                                                <div class="form-group">
                                                    <label>{{ "$scoreType->name ($scoreType->percentage%)"}}</label>
                                                    <input
                                                        wire:model="dynamicScores.{{ $scoreType->id }}"
                                                        type="number"
                                                        step="0.01"
                                                        class="form-control"
                                                        placeholder="{{ $scoreType->name }}"
                                                        value="{{ $dynamicScores[$scoreType->id] ?? '' }}"
                                                    >
                                                    @error('dynamicScores.' . $scoreType->id)
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        @empty
                                            <x-no-result title="No score type"
                                                         description="No score type found for this subject"/>
                                        @endforelse
                                    @endif

                                </div>
                            @endif

                        </div>
                        @if(collect($scoreTypes)->isNotEmpty())
                            <div class="modal-footer">
                                <div class="hstack gap-2 justify-content-end">
                                    <x-button label="Save" class="btn btn-success"/>
                                </div>
                            </div>
                        @endif

                    </form>
                </div>
            </div>
        </div>

        <!-- Backdrop -->
        <div x-show="showResult" class="modal-backdrop fade show" style="z-index: 1040;"></div>
    </div>

    {{--    end modal--}}

    {{--    Promote Students--}}
    <div x-data="{ promoteStudents: false }"
         x-init="promoteStudents = false" x-cloak
         @promote-students.window="promoteStudents = true"
         @close-modal.window="promoteStudents = false">

        <!-- Modal -->
        <div class="modal fade"
             :class="{ 'show': promoteStudents }"
             :style="promoteStudents ? 'display: block;' : 'display: none;'"
             tabindex="-1" role="dialog"
             aria-labelledby="attachScore"
             aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header p-3 bg-primary-subtle">
                        <h5 class="modal-title" id="attachScore">Promote Students</h5>
                        <button type="button" class="btn-close" @click="promoteStudents = false"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 text-muted">
                            <p class="mb-0">
                                Use this form to promote students to the next academic level. Ensure you have selected
                                the appropriate
                                class before assigning promotions. This process will update the academic standing of the
                                students.
                            </p>
                        </div>

                        <form wire:submit.prevent="promoteStudents">

                            <x-input-select model="promotion.class_id" :options="$classes"
                                            key="id" value="name" :choices="false"
                                            label="Promote To" wrapper_class="col-md-12"
                            />


                            <div class="hstack gap-2 justify-content-end">
                                <x-button class="btn btn-primary" label="Promote Now"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backdrop -->
        <div x-show="promoteStudents" class="modal-backdrop fade show" style="z-index: 1040;"></div>
    </div>

    {{--    Mark As Completed--}}
    <div x-data="{ markCompleted: false }"
         x-init="markCompleted = false" x-cloak
         @mark-completed.window="markCompleted = true"
         @close-modal.window="markCompleted = false">

        <!-- Modal -->
        <div class="modal fade"
             :class="{ 'show': markCompleted }"
             :style="markCompleted ? 'display: block;' : 'display: none;'"
             tabindex="-1" role="dialog"
             aria-labelledby="markCompleted"
             aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header p-3 bg-primary-subtle">
                        <h5 class="modal-title" id="attachScore">Mark Students As Completed</h5>
                        <button type="button" class="btn-close" @click="markCompleted = false"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 text-muted">
                            <p class="mb-0 text-danger">
                                Use this form to mark students as graduate. Ensure you have selected the appropriate
                                students before marking graduations. This process will update the academic standing of
                                the students,
                                and these student will no longer be available in this class
                            </p>
                        </div>

                        <form wire:submit.prevent="markCompleted">

                            <div class="hstack gap-2 justify-content-end">
                                <x-button class="btn btn-primary" label="Mark Completed"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backdrop -->
        <div x-show="markCompleted" class="modal-backdrop fade show" style="z-index: 1040;"></div>
    </div>

    {{--    remarks--}}
    <div x-data="{ showRemark: false }"
         x-init="showRemark = false" x-cloak
         @open-remark.window="showRemark = true"
         @close-modal.window="showRemark = false">

        <!-- Modal -->
        <div class="modal fade"
             :class="{ 'show': showRemark }"
             :style="showRemark ? 'display: block;' : 'display: none;'"
             tabindex="-1" role="dialog"
             aria-labelledby="attachScore"
             aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header p-3 bg-primary-subtle">
                        <h5 class="modal-title" id="attachScore">Remarks ({{$studentForRemark?->fullname}})</h5>
                        <button type="button" class="btn-close" @click="showRemark = false" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="addRemark">

                            <x-enum-select enum="App\Enum\Remark\Type" model="remarkOption.type"
                                            label="Remark Type" wrapper_class="col-md-12" :live="true"
                            />

                            <div wire:key="remark-type-{{ $remarkOption['type'] }}-{{ $loop->index ?? 0 }}">


                            @switch($remarkOption['type'])
                                    @case('form-master')

                                        <x-enum-select enum="App\Enum\Remark\Interest" model="remarkOption.interest"
                                                       label="Interest" wrapper_class="col-md-12" />
                                        <x-enum-select enum="App\Enum\Remark\Attitude" model="remarkOption.attitude"
                                                       label="Attitude" wrapper_class="col-md-12" />
                                        @break

                                    @case('head-master')
                                        <x-enum-select enum="App\Enum\Remark\Conduct" model="remarkOption.conduct"
                                                       label="Conduct" wrapper_class="col-md-12" />
                                        <x-enum-select enum="App\Enum\Remark\Remark" model="remarkOption.remark"
                                                       label="Remark" wrapper_class="col-md-12" />
                                        @break
                                @endswitch
                            </div>


                            <div class="hstack gap-2 justify-content-end">
                                <x-button class="btn btn-primary" label="Update"/>
                            </div>



                        </form>


                    </div>
                </div>
            </div>
        </div>

        <!-- Backdrop -->
        <div x-show="showRemark" class="modal-backdrop fade show" style="z-index: 1040;"></div>
    </div>
    {{--    END MODALS--}}

    {{--    assign score type--}}
    <div x-data="{ showAttach: false }"
         x-init="showAttach = false" x-cloak
         @open-attach.window="showAttach = true"
         @close-modal.window="showAttach = false">

        <!-- Modal -->
        <div class="modal fade"
             :class="{ 'show': showAttach }"
             :style="showAttach ? 'display: block;' : 'display: none;'"
             tabindex="-1" role="dialog"
             aria-labelledby="attachScore"
             aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header p-3 bg-primary-subtle">
                        <h5 class="modal-title" id="attachScore">Assign ScoreType</h5>
                        <button type="button" class="btn-close" @click="showAttach = false" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="attachScoreToSubject">
                            <x-input-select model="assignScoreType.subject_id" :options="$class->subjects"
                                            key="id" value="name" :choices="false"
                                            label="Select Subject" wrapper_class="col-md-12"
                            />

                            <x-input-select model="assignScoreType.score_type_id" :options="$class->scoreTypes"
                                            key="id" value="name" :choices="false"
                                            label="Select ScoreType" wrapper_class="col-md-12"/>
                            <div class="hstack gap-2 justify-content-end">
                                <x-button class="btn btn-primary" label="Assign"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backdrop -->
        <div x-show="showAttach" class="modal-backdrop fade show" style="z-index: 1040;"></div>
    </div>
    {{--    END MODALS--}}
</div>

@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection


