@section("title", 'Routes')


<div class="row">
    <div class="col-lg-12">
        <div class="card" id="customerList">
            <div class="card-header border-bottom-dashed">

                <div class="row g-4 align-items-center">
                    <div class="col-sm">
                        <div>
                            <h5 class="card-title mb-0">Transportation Routes</h5>
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        <div class="d-flex flex-wrap align-items-start gap-2">

                            <x-link label="Create Route" route="transportations.create" class="btn btn-primary"
                                    icon="ri-add-line align-bottom me-1"/>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body border-bottom-dashed border-bottom">
                <form>
                    <div class="row g-3">
                        <div class="col-xl-6">
                            <div class="search-box">
                                <input wire:model.live="search" type="text" class="form-control search"
                                       placeholder="Search something...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-xl-6">
                            <div class="row g-3">

                                <div class="col-sm-4">
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
                <div>
                    <div class="table-responsive table-card mb-1">
                        <table class="table table-nowrap align-middle">
                            <thead class="text-muted table-light">
                            <tr class="text-uppercase">
                                <th class="sort" data-sort="id">#</th>
                                <th class="sort">Route Name</th>
                                <th class="sort">Fare (GHS)</th>
                                <th class="sort" >Distance</th>
                                <th class="sort" >Description</th>
                                <th class="sort" >Created On</th>
                                <th class="sort" >Action</th>
                            </tr>
                            </thead>
                            <tbody class="list form-check-all">
                            @forelse($routes as $route)
                                <tr>
                                    <td class="customer_name">{{$loop->iteration}}</td>
                                    <td class="customer_name">{{$route->route}}</td>
                                    <td class="customer_name">{{$route->fee->amount}}</td>
                                    <td class="customer_name">{{$route->distance}}</td>
                                    <td class="customer_name">{{$route->description}}</td>
                                    <td class="date">{{$route->created_at->format('d F, Y')}}</td>

                                    <td>
                                        <ul class="list-inline hstack gap-2 mb-0">
                                            <li class="list-inline-item edit" title="Edit">
                                                <x-link route="transportations.edit" param="{{$route->uuid}}"
                                                        icon="ri-pencil-fill fs-24"
                                                        class="text-primary d-inline-block edit-item-btn"/>
                                            </li>
                                            <li class="list-inline-item" data-bs-toggle="tooltip"
                                                data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                                                <a class="text-danger d-inline-block remove-item-btn"
                                                   data-bs-toggle="modal" href="#deleteOrder">
                                                    <i class="ri-delete-bin-5-fill fs-24"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <x-no-result description="No Student found"/>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                    </div>
                    <x-paginate :collection="$routes"/>
                </div>

            </div>
        </div>

    </div>
    <!--end col-->
</div>

@section('script')

    <script src="{{ URL::asset('build/js/app.js') }}"></script>

@endsection
