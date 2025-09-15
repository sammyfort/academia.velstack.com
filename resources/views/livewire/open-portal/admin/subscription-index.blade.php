<div>
    @section("title", 'Subscriptions')
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">

                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">Subscription List</h5>
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
                    <div>
                        <div class="table-responsive table-card mb-1">
                            <table class="table table-nowrap align-middle">
                                <thead class="text-muted table-light">
                                <tr class="text-uppercase">
                                    <th class="sort" data-sort="id">#</th>
                                    <th class="sort" data-sort="customer_name">School </th>
                                    <th class="sort" data-sort="customer_name">Amount </th>
                                    <th class="sort" data-sort="product_name">Reference</th>
                                    <th class="sort" data-sort="product_name">Channel</th>
                                    <th class="sort" data-sort="date">Status</th>
                                    <th class="sort" data-sort="city">Response</th>
                                    <th class="sort" data-sort="city">Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($subscriptions as $subscription)
                                    <tr>
                                        <td class="customer_name">{{$loop->iteration}}</td>
                                        <td class="customer_name">{{$subscription->school->name}}</td>
                                        <td class="customer_name">{{$subscription->amount}}</td>
                                        <td class="date">{{$subscription->reference}} </td>
                                        <td class="date">{{$subscription->channel}} </td>
                                        <td class="date">{{$subscription->status}} </td>
                                        <td class="date">{{$subscription->response}} </td>
                                        <td class="date">{{$subscription->created_at->format('d F, Y')}}</td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">
                                            <x-no-result description="No Subscription found"/>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                        </div>
                        <x-paginate :collection="$subscriptions"/>
                    </div>

                </div>
            </div>
            <livewire:modal.delete-modal/>
        </div>
        <!--end col-->

    </div>

    @section('script')


        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection
</div>


