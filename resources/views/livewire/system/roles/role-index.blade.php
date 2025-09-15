<div>
    @section("title", 'Roles')
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">

                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">System Roles</h5>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex flex-wrap align-items-start gap-2">
                                <x-link label="Add Role" route="roles.create" class="btn btn-primary"
                                        icon="ri-add-line align-bottom me-1"/>
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
                    @forelse($roles as $role)
                        <div class="row" id="candidate-list">
                            <div class="col-md-6 col-lg-12">
                                <div class="card mb-0">
                                    <div class="card-body">
                                        <div class="d-lg-flex align-items-center flex-wrap">

                                            <div class="ms-lg-3 my-3 my-lg-0">
                                                <a href="https://">
                                                    <h5 class="fs-16 mb-2">{{$role->name}}</h5>
                                                </a>
                                                <p class="text-muted mb-0">Permissions: {{ $role->permissions->count()}}</p>
                                            </div>

                                            <!-- Permissions -->
                                            <div class="d-flex gap-2 flex-wrap mt-3 text-muted mb-3">
                                                @forelse($role->permissions as $permission)
                                                    <span title="Assigned"
                                                          class="badge bg-success-subtle text-success d-inline-flex align-items-center">{{str($permission->name)->replace('.', '')->headline()}}
                                              </span>
                                                @empty
                                                    <span
                                                        class="badge bg-danger-subtle text-danger d-inline-flex align-items-center me-2">No Permission</span>
                                                @endforelse
                                            </div>

                                            <!-- Add Permission Button -->
                                            <div class="mt-8 mt-lg-0 ms-auto">

                                                <a class="btn btn-warning"
                                                   @click="$dispatch('open-role', { role_id: {{ $role->id }} })">Permission</a>
                                                <a class="btn btn-primary"
                                                   href="{{route('roles.edit', $role)}}">Edit Role</a>

                                                @if($role->name === \App\Enum\RoleEnum::SUPER_ADMINISTRATOR->value)
                                                    <a class="btn btn-danger disabled"
                                                       >Delete Role
                                                    </a>
                                                @else
                                                    <a class="btn btn-danger"
                                                       @click="$dispatch('open-delete-modal', {model: 'Role',  modelId:{{ $role->id }},recordName: 'Role' })">Delete Role
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                    @empty
                        <x-no-result/>
                    @endforelse

                    <x-paginate :collection="$roles"/>

                </div>
            </div>
            <livewire:modal.delete-modal/>
        </div>
        <!--end col-->
    </div>


    <!-- Modal -->
    <div x-data="{ openRole: false }"
         x-init="openRole = false" x-cloak
         @open-role.window="openRole = true"
         @close-modal.window="openRole = false">

        <div class="modal fade"
             aria-hidden="true"
             :class="{ 'show': openRole }"
             :style="openRole ? 'display: block;' : 'display: none;'"
             tabindex="-1" role="dialog"
             aria-labelledby="openRole"
             aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header p-3 ps-4 bg-success-subtle">
                        <h5 class="modal-title" id="inviteMembersModalLabel">Assign Permissions</h5>
                        <button
                            type="button"
                            class="btn-close"
                            @click="openRole = false; $wire.resetRole()"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                        </button>
                    </div>
                    @if($roleModel)
                        <div class="modal-body p-4">
                            <div class="col-lg-12">
                                <div class="text-center">
                                    <h5 class="fs-13 mt-3">{{$roleModel->name}}</h5>
                                </div>
                            </div>
                            <div class="mb-3 me-2 d-flex justify-content-between">
                                <a class="btn btn-success btn-sm" wire:click="assignAllPermissions">Add All</a>
                                @if($roleModel->name != \App\Enum\RoleEnum::SUPER_ADMINISTRATOR->value)
                                    <a class="btn btn-danger btn-sm" wire:click="revokeAllPermissions">Revoke All</a>
                                @endif

                            </div>


                            <div class="search-box mb-3">
                                <input wire:model.live="permissionSearch" type="text"
                                       class="form-control bg-light border-light" placeholder="Search permission ...">
                                <i class="ri-search-line search-icon"></i>
                            </div>

                            <div class="mx-n4 px-4" style="max-height: 400px; overflow-y: auto;">
                                <div class="vstack gap-3">
                                    @foreach($permissions as $permission)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs flex-shrink-0 me-3">
                                                <i class="ri-lock-line avatar-sm img-fluid rounded-circle fs-24"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fs-13 mb-0"><a href="#" class="text-body d-block">
                                                        {{str($permission->name)->replace('.', '')->headline()}}
                                                    </a>
                                                </h5>
                                            </div>
                                            <div class="flex-shrink-0">
                                                @if($roleModel->hasPermissionTo($permission->name))
                                                    @if(trim($roleModel->name) === \App\Enum\RoleEnum::SUPER_ADMINISTRATOR->value)

                                                    <a class="btn btn-danger btn-sm disabled">Cannot Revoke</a>
                                                    @else
                                                        <a wire:click="revokePermission({{$roleModel->id}}, {{$permission->id}})"
                                                           class="btn btn-danger btn-sm">Revoke</a>
                                                    @endif

                                                @else
                                                    <a wire:click="assignPermission({{$roleModel->id}}, '{{ $permission->name }}')"
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
        <div x-show="openRole" class="modal-backdrop fade show" style="z-index: 1040;"></div>
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




