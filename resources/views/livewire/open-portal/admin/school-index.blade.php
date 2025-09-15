<div>
    @section("title", 'Students')
    <div class="row" wire:ignore>
        <x-card label="Total Students" target="{{\App\Models\School::count()}}" icon="ri-graduation-cap-line"/>


    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">

                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">Schools List</h5>
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

                                    <x-enum-select model="region" enum="App\Enum\Region"
                                                   placeholder="Filter By Region" bind=".live"
                                                    key="id" value="name" id="region" wrapper_class="col-sm-2"/>

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
                        @forelse($schools as $school)
                            <div class="card team-box">
                                <div class="card-body px-4">
                                    <div class="row align-items-center team-row">

                                        <div class="col-lg-3 col">
                                            <div class="team-profile-img">
                                                <div class="avatar-lg img-thumbnail rounded-circle">
                                                    <img src="{{$school->favicon()}}" alt=""
                                                         class="img-fluid d-block rounded-circle"/>
                                                </div>
                                                <div class="team-content">
                                                    <a href="#" class="d-block">
                                                        <h5 class="fs-16 mb-1">{{$school->name}}</h5>
                                                    </a>
                                                    <p class="text-muted mb-0">{{$school->region}}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col">
                                            <div class="row text-muted text-center">
                                                <div class="col-6 border-end border-end-dashed">
                                                    <h6 class="mb-1">Region</h6>
                                                    <p class="text-muted mb-0">{{$school->district}}</p>
                                                </div>
                                                <div class="col-6">
                                                    <h6 class="mb-1">Joined Date</h6>
                                                    <p class="text-muted mb-0">{{$school->created_at->format('d F, Y')}}</p>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-lg-3 col">
                                            <div class="row text-muted text-center">
                                                <div class="col-6 border-end border-end-dashed">
                                                    <h6 class="mb-1">Students</h6>
                                                    <p class="text-muted mb-0">{{$school->students()->count()}}</p>
                                                </div>
                                                <div class="col-6">
                                                    <h6 class="mb-1">Staff</h6>
                                                    <p class="text-muted mb-0">{{$school->staff()->count()}}</p>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-lg-3 col">
                                            <div class="row text-muted text-center">
                                                <div class="col-6 border-end border-end-dashed">
                                                    <h6 class="mb-1">Payments </h6>
                                                    <p class="text-muted mb-0">{{$school->transactions()->count()}}</p>
                                                </div>
                                                <div class="col-6">
                                                    <h6 class="mb-1">Subscription Due</h6>
                                                    <p class="text-danger mb-0">{{$school->subscription->expires_at->format('d M, Y')}}</p>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>


                        @empty
                            <x-no-result description="No Student found"/>
                        @endforelse
                        <x-paginate :collection="$schools"/>
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


