<div>
    @section("title", 'Payment History')
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">

                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">Payment History</h5>
                            </div>
                        </div>
                        <div class="col-sm-auto">

                            <div class="d-flex flex-wrap align-items-start gap-2">
                                <x-link label="Create Payment" route="finance.pay.bill" class="btn btn-primary"
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
                    <div>
                        <div class="table-responsive table-card mb-1">
                            <table class="table table-nowrap align-middle">
                                <thead class="text-muted table-light">
                                <tr class="text-uppercase">
                                    <th class="sort" data-sort="id">#</th>
                                    <th class="sort" data-sort="customer_name">Student Name</th>
                                    <th class="sort" data-sort="product_name">Index Number</th>
                                    <th class="sort" data-sort="product_name">Bills</th>
                                    <th class="sort" data-sort="date">Amount</th>
                                    <th class="sort" data-sort="date">Receipt Number</th>
                                    <th class="sort" data-sort="date">Status</th>
                                    <th class="sort" data-sort="city">Created On</th>
                                    <th class="sort" data-sort="city">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($payments as $payment)
                                    <tr>
                                        <td class="customer_name">{{$loop->iteration}}</td>
                                        <td class="customer_name">{{$payment->student->fullname}}</td>
                                        <td class="date">{{$payment->student->index_number}} </td>
                                        <td class="date">{{$payment->bills_count}} </td>
                                        <td class="date">GHS {{$payment->amount}} </td>
                                        <td class="date"> {{$payment->uuid}} </td>
                                        <td>
                                             <span class="badge bg-success-subtle text-success text-uppercase">Success</span>
                                        </td>
                                        <x-date-field :date="$payment->created_at"/>

                                        <td>
                                            <x-table-actions >
                                                <li class="list-inline-item" title="Remove">
                                                    <a onclick="printReceipt('{{route('finance.payment.receipt', $payment->uuid)}}')"  class="btn btn-primary">
                                                        <i class="ri-printer-fill fs-16"></i> Receipt</a>
                                                </li>
                                                <li class="list-inline-item" title="Remove">
                                                    <a class="text-danger d-inline-block remove-item-btn"
                                                       @click="$dispatch('open-delete-modal', {model: 'Payment',
                                                    modelId:{{ $payment->id }},recordName: 'Payment' })">
                                                        <i class="ri-delete-bin-5-fill fs-24"></i>
                                                    </a>
                                                </li>
                                            </x-table-actions>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">
                                            <x-no-result description="No payment found"/>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                        </div>
                        <x-paginate :collection="$payments"/>
                    </div>

                </div>
            </div>

        </div>
        <!--end col-->
    </div>
    <livewire:modal.delete-modal />
    @section('script')

        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection
</div>


