<div>
    @section("title", 'Students')
    <div class="row" wire:ignore>
        @php

            $school = school()->loadCount(['students', 'alumni']);
        @endphp
        <x-card label="Total Students" target="{{school()->students_count}}" icon="ri-graduation-cap-line"/>
        <x-card label="Alumni" target="{{$alumni->count()}}" icon="ri-bookmark-3-line" bg="warning"/>

    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">

                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">Students List</h5>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex flex-wrap align-items-start gap-2">
                                <x-link label="Admit Student" route="students.admit" class="btn btn-primary"
                                        icon="ri-add-line align-bottom me-1"/>

                                @if(school()->students()->exists())
                                    <button wire:click="exportStudents" class="btn btn-success add-btn">
                                        <i class="ri-file-excel-2-fill align-bottom me-1"></i> Export Students
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body border-bottom-dashed border-bottom">
                    <form>
                        <div class="row g-3">
                            <div class="col-xl-12">
                                <div class="row g-3">
                                    <div class="col-xl-3">
                                        <div class="search-box">
                                            <input wire:model.live="search" type="text" class="form-control search"
                                                   placeholder="Search something...">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div>
                                            <select id="alumn" wire:model.live="queryAlumni" class="form-control form-select">
                                                <option value="">Sort By</option>
                                                <option value="true">Alumni</option>
                                            </select>
                                        </div>
                                    </div>

                                    <x-input-select model="class_id" :options="$classes" placeholder="Filter By Class" bind=".live"
                                                    key="id" value="name" id="classes" wrapper_class="col-sm-2"/>
                                    <x-input-select model="parent_id" :options="$parents" placeholder="Filter By Parent" bind=".live"
                                                    key="id" value="name" id="parents" wrapper_class="col-sm-2"/>
                                    <!--end col-->
                                    <div class="col-sm-2">
                                        <div>
                                            <button type="button" class="btn btn-primary w-100"
                                                    wire:click.prevent="resetFilter()">
                                                <i class="ri-equalizer-fill me-2 align-bottom"></i>Filters
                                            </button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                            </div>
                        </div>
                        <!--end row-->
                    </form>
                </div>
                <div class="card-body">

                    <div class="team-list list-view-filter">
                        @forelse($students as $student)
                            <div class="card team-box">
                                <div class="card-body px-4">
                                    <div class="row align-items-center team-row">
                                        <div class="col team-settings">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <div class="flex-shrink-0 me-2">
                                                        <button type="button" class="btn fs-16 p-0 favourite-btn {{$student->is_completed ? 'active' : ''}}">
                                                            <i class="ri-star-fill"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col text-end dropdown">
                                                    <a href="javascript:void(0);" data-bs-toggle="dropdown"
                                                       aria-expanded="false">
                                                        <i class="ri-more-fill fs-17"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="{{route('students.edit', $student->uuid)}}"><i
                                                                    class="ri-pencil-fill text-muted me-2 align-bottom"></i>Edit</a>
                                                        </li>
                                                        <li>
                                                            @if($student->is_completed)
                                                                <a wire:click="unGraduateStudent({{$student->id}})"
                                                                   class="dropdown-item"  >
                                                                    <i class="ri-star-fill text-muted me-2 align-bottom">
                                                                    </i>Un-Graduate
                                                                </a>
                                                            @else
                                                                <a wire:click="graduateStudent({{$student->id}})" class="dropdown-item" >
                                                                    <i class="ri-star-fill text-muted me-2 align-bottom">
                                                                    </i>Graduate
                                                                </a>
                                                            @endif

                                                        </li>
                                                        <li><a class="dropdown-item" href="javascript:void(0);"
                                                               @click="$dispatch('open-delete-modal', {model: 'Student',
                                                                modelId:{{ $student->id }},recordName: 'Student' })">
                                                                <i class="ri-delete-bin-5-fill text-muted me-2 align-bottom"></i>Delete</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col">
                                            <div class="team-profile-img">
                                                <div class="avatar-lg img-thumbnail rounded-circle">
                                                    <img src="{{$student->image()}}" alt=""
                                                         class="img-fluid d-block rounded-circle"/>
                                                </div>
                                                <div class="team-content">
                                                    <a href="#" class="d-block">
                                                        <h5 class="fs-16 mb-1">{{$student->fullname}}</h5>
                                                    </a>
                                                    <p class="text-muted mb-0">{{$student->class->name}}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col">
                                            <div class="row text-muted text-center">
                                                <div class="col-6 border-end border-end-dashed">
                                                    <h6 class="mb-1">Subjects</h6>
                                                    <p class="text-muted mb-0">{{$student->subjects_count}}</p>
                                                </div>
                                                <div class="col-6">
                                                    <h6 class="mb-1">Admission Date</h6>
                                                    <p class="text-muted mb-0">{{$student->created_at->format('d F, Y')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col">
                                            <div class="text-end">
                                                <a href="{{route('students.show', $student->uuid)}}" class="btn btn-light view-btn">View Profile</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        @empty
                            <x-no-result description="No Student found"/>
                        @endforelse
                            <x-paginate :collection="$students"/>
                        <!--end card-->
                    </div>
                </div>
            </div>
            <livewire:modal.delete-modal />
        </div>
        <!--end col-->
    </div>

    @section('script')
        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection
</div>


