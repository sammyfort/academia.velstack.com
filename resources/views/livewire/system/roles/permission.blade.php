<div>
    @section("title", 'Permissions')
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">

                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">Employee Permissions</h5>
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
                    @forelse($users as $staff)
                        <div class="row" id="candidate-list">
                            <div class="col-md-6 col-lg-12">
                                <div class="card mb-0">
                                    <div class="card-body">
                                        <div class="d-lg-flex align-items-center flex-wrap">
                                            <!-- Worker Profile -->
                                            <div class="flex-shrink-0">
                                                <div class="avatar-sm rounded">
                                                    <img src="{{$staff->image()}}" alt=""
                                                         class="avatar-xs rounded-circle">
                                                </div>
                                            </div>
                                            <div class="ms-lg-3 my-3 my-lg-0">
                                                <a href="https://">
                                                    <h5 class="fs-16 mb-2">{{$staff->fullname}}</h5>
                                                </a>
                                                <p class="text-muted mb-0">Roles: {{ $staff->roles->count() }}</p>
                                            </div>

                                            <!-- Permissions -->
                                            <div class="d-flex gap-2 flex-wrap mt-3 text-muted">
                                                @forelse($staff->roles as $role)
                                                    <span title="Assigned"
                                                          class="badge bg-success-subtle text-success d-inline-flex align-items-center">{{str($role->name)->replace('.', '')->headline()}}

                                              </span>
                                                @empty
                                                    <span
                                                        class="badge bg-danger-subtle text-danger d-inline-flex align-items-center">No Role</span>
                                                @endforelse
                                            </div>

                                            <!-- Add Permission Button -->
                                            <div class="mt-3 mt-lg-0 ms-auto">
                                                <a class="btn btn-success"
                                                   @click="$dispatch('open-staff', { staff_id: {{ $staff->id }} })">Add Role</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                        <x-no-result/>
                    @endforelse

                    <x-paginate :collection="$users"/>

                </div>
            </div>
            <livewire:modal.delete-modal/>
        </div>
        <!--end col-->
    </div>


    <!-- Modal -->
    <div x-data="{ openStaff: false }"
         x-init="openStaff = false" x-cloak
         @open-staff.window="openStaff = true"
         @close-modal.window="openStaff = false">

        <div class="modal fade"
             aria-hidden="true"
             :class="{ 'show': openStaff }"
             :style="openStaff ? 'display: block;' : 'display: none;'"
             tabindex="-1" role="dialog"
             aria-labelledby="openStaff"
             aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header p-3 ps-4 bg-success-subtle">
                        <h5 class="modal-title" id="inviteMembersModalLabel">Assign Roles</h5>
                        <button
                            type="button"
                            class="btn-close"
                            @click="openStaff = false; $wire.resetStaff()"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                        </button>
                    </div>
                    @if($staffModel)
                        <div class="modal-body p-4">
                            <div class="col-lg-12">
                                <div class="text-center">
                                    <div class="position-relative d-inline-block">

                                        <div class="avatar-lg p-1">
                                            <div class="avatar-title bg-light rounded-circle">
                                                <img src="{{$staffModel->image()}}"
                                                     alt="" id="lead-img"
                                                     class="avatar-md rounded-circle object-fit-cover">
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="fs-13 mt-3">{{$staffModel->fullname}}</h5>
                                </div>
                            </div>
                            <div class="mb-3 me-2 d-flex justify-content-between">
                                <a class="btn btn-success btn-sm" wire:click="assignAllRoles">Add All</a>
                                <a class="btn btn-danger btn-sm" wire:click="revokeAllRoles">Revoke All</a>
                            </div>


                            <div class="search-box mb-3">
                                <input wire:model.live="permissionSearch" type="text"
                                       class="form-control bg-light border-light" placeholder="Search here...">
                                <i class="ri-search-line search-icon"></i>
                            </div>

                            <div class="mx-n4 px-4" style="max-height: 400px; overflow-y: auto;">
                                <div class="vstack gap-3">
                                    @foreach($roles as $role)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs flex-shrink-0 me-3">
                                                <i class="ri-group-line avatar-sm img-fluid rounded-circle fs-24"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fs-13 mb-0"><a href="#"
                                                                          class="text-body d-block">

                                                        {{str($role->name)->replace('.', '')->headline()}}
                                                    </a>
                                                </h5>
                                            </div>
                                            <div class="flex-shrink-0">
                                                @if($staffModel->hasRole($role->name))
                                                    <a wire:click="revokeRole({{$staffModel->id}}, {{$role->id}})"
                                                       class="btn btn-danger btn-sm">Revoke</a>
                                                @else
                                                    <a wire:click="assignRole({{$staffModel->id}}, '{{ $role->name }}')"
                                                       class="btn btn-success btn-sm">Add</a>
                                                @endif

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <!-- end list -->
                            </div>
                        </div>

                    @else
                        <div class="modal-body p-4">
                            <div class="col-lg-12">
                                <div class="text-center">
                                    <div class="position-relative d-inline-block">
                                        <div class="avatar-lg p-1 skeleton-loader"></div>
                                    </div>
                                    <div class="skeleton-loader fs-13 mt-3" style="width: 150px; height: 20px;"></div>
                                </div>
                            </div>

                            <div class="mb-3 me-2 d-flex justify-content-between">
                                <div class="skeleton-loader btn btn-sm" style="width: 80px; height: 30px;"></div>
                                <div class="skeleton-loader btn btn-sm" style="width: 80px; height: 30px;"></div>
                            </div>

                            <div class="search-box mb-3">
                                <div class="skeleton-loader" style="height: 36px;"></div>
                            </div>

                            <div class="mx-n4 px-4" style="max-height: 400px; overflow-y: auto;">
                                <div class="vstack gap-3">
                                    <div class="d-flex align-items-center skeleton-loader">
                                        <div class="avatar-xs flex-shrink-0 me-3 skeleton-loader" style="width: 30px; height: 30px;"></div>
                                        <div class="flex-grow-1">
                                            <div class="skeleton-loader" style="height: 20px;"></div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="skeleton-loader btn btn-sm" style="width: 50px; height: 30px;"></div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center skeleton-loader">
                                        <div class="avatar-xs flex-shrink-0 me-3 skeleton-loader" style="width: 30px; height: 30px;"></div>
                                        <div class="flex-grow-1">
                                            <div class="skeleton-loader" style="height: 20px;"></div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="skeleton-loader btn btn-sm" style="width: 50px; height: 30px;"></div>
                                        </div>
                                    </div>
                                    <!-- More skeleton loaders can be added as needed -->
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
                <!-- end modal-content -->
            </div>
            <!-- modal-dialog -->
        </div>
        <!-- Backdrop -->
        <div x-show="openStaff" class="modal-backdrop fade show" style="z-index: 1040;"></div>
    </div>

    <!-- end modal -->

    @section('script')

        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection

    <style>
        .skeleton-loader {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite ease-in-out;
            border-radius: 4px;
        }

        .skeleton-loader.btn {
            background-color: #f0f0f0;
            border-radius: 4px;
            height: 36px;
        }

        .skeleton-loader.avatar-lg {
            width: 70px;
            height: 70px;
            border-radius: 50%;
        }

        .skeleton-loader.avatar-xs {
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }

        @keyframes loading {
            0% {
                background-position: -200% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }

    </style>
</div>




