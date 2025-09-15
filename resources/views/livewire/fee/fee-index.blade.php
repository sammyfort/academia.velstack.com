<div>
    @section("title", 'Fees')
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">

                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">Fees List</h5>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex flex-wrap align-items-start gap-2">
                                <x-link label="Create Fee" route="finance.fee.create" class="btn btn-primary"
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
                                    <th class="sort" data-sort="customer_name">Fee Name</th>
                                    <th class="sort" data-sort="product_name">Amount</th>
                                    <th class="sort" data-sort="product_name">Revenue Generated</th>
                                    <th class="sort" data-sort="date">Outstanding</th>
                                    <th class="sort" data-sort="date">Students Owing</th>
                                    <th class="sort" data-sort="date">Academic Year</th>
                                    <th class="sort" data-sort="city">Created On</th>
                                    <th class="sort" data-sort="city">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($fees as $fee)
                                    <tr>
                                        <td class="customer_name">{{$loop->iteration}}</td>
                                        <td class="customer_name">{{$fee->name}}</td>
                                        <td class="date">{{$fee->amount}} </td>
                                        <td class="date">GHS  {{ $fee->payment_bills_sum_amount_paid ?? 0.00 }}</td>
                                        <td>GHS {{ $fee->bills_sum_amount - $fee->payment_bills_sum_amount_paid }}</td>
                                        <td><span class="badge bg-danger-subtle text-danger text-uppercase"> {{$fee->students_with_debts_relation_count}}</span>
                                        </td>
                                        <td>{{$fee->term->name}}</td>
                                        <x-date-field :date="$fee->created_at"/>

                                        <td>
                                            <x-table-actions :id="$fee->uuid" edit="finance.fee.edit"  >
                                                <li class="list-inline-item" title="Remove">
                                                    <a class="text-danger d-inline-block remove-item-btn"
                                                       @click="$dispatch('open-delete-modal', {model: 'Fee',  modelId:{{ $fee->id }},recordName: 'Fee' })">
                                                        <i class="ri-delete-bin-5-fill fs-24"></i>
                                                    </a>
                                                </li>
                                            </x-table-actions>
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">
                                            <x-no-result description="No Fee found"/>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                        </div>
                        <x-paginate :collection="$fees"/>
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


