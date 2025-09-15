<div>
    @section("title", 'Outgoing Transfers')
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">

                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">Outgoing Transfers</h5>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex flex-wrap align-items-start gap-2">
                                <x-link label="New Transfer" route="transfers.initiate" class="btn btn-primary"
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
                    <div>
                        <div class="table-responsive table-card mb-1">
                            <table class="table table-nowrap align-middle">
                                <thead class="text-muted table-light">
                                <tr class="text-uppercase">
                                    <th class="sort" data-sort="id">#</th>
                                    <th class="sort" data-sort="customer_name">Student Name</th>
                                    <th class="sort" data-sort="product_name">Index Number</th>
                                    <th class="sort" data-sort="product_name">Class</th>
                                    <th class="sort" data-sort="customer_name">Transfer To</th>
                                    <th class="sort" data-sort="date">Requested On</th>
                                    <th class="sort" data-sort="date">Status</th>
                                    <th class="sort" data-sort="city">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($outgoingTransfers as $transfer)
                                    <tr>
                                        <td class="customer_name">{{$loop->iteration}}</td>
                                        <td>
                                            <x-td-image label="{{$transfer->transferable->fullname}}"
                                                        src="{{$transfer->transferable->image()}}"
                                                        size="xs"
                                                        link="{{route('students.show', $transfer->transferable->uuid)}}"/>
                                        </td>

                                        <td class="date">{{$transfer->transferable->index_number}} </td>
                                        <td class="date">{{$transfer->transferable->class->name}} </td>
                                        <td class="date">{{$transfer->toSchool->name}} </td>
                                        <td class="date">{{$transfer->initiated_at->format('d F, Y h:i')}} </td>
                                        <td>
                                            <span class="badge bg-primary">{{str($transfer->status)->upper()}}</span>
                                        </td>
                                        <td>
                                            <ul class="list-inline hstack gap-2 mb-0">
                                                <li class="list-inline-item" title="Are you sure ?">
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                            wire:click.prevent="cancel({{ $transfer->id }})">
                                                        Cancel Transfer
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
                                </tbody>
                            </table>

                        </div>
                        <x-paginate :collection="$outgoingTransfers"/>

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


