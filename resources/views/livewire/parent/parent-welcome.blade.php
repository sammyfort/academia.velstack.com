<div>
    @section("title", 'Students')

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
                                                    <p class="text-muted mb-0">{{$student->school->name}}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col">
                                            <div class="row text-muted text-center">
                                                <div class="col-6 border-end border-end-dashed">
                                                    <h6 class="mb-1">Class</h6>
                                                    <p class="text-muted mb-0">{{$student->class->name}}</p>
                                                </div>
                                                <div class="col-6">
                                                    <h6 class="mb-1">Admission Date</h6>
                                                    <p class="text-muted mb-0">{{$student->created_at->format('d F, Y')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col">
                                            <div class="text-end">
                                                <a href="{{route('parents.student', $student->uuid)}}" class="btn btn-light view-btn">View Profile</a>
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


