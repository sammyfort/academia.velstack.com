@section("title", 'Expenses')


<div class="row">
    <div class="col-lg-12">
        <div class="card" id="customerList">
            <div class="card-header border-bottom-dashed">

                <div class="row g-4 align-items-center">
                    <div class="col-sm">
                        <div>
                            <h5 class="card-title mb-0">Expenses List</h5>
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        <div class="d-flex flex-wrap align-items-start gap-2">

                            <x-link label="Create Expense" route="expenses.create" class="btn btn-primary"
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
                                    <div class="">
                                        <input wire:model.live="date" type="text" class="form-control"
                                               id="datepicker-range"
                                               data-provider="flatpickr"
                                                placeholder="Select date">
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-sm-4">
                                    <div>
                                        <x-enum-select enum="App\Enum\ExpenseCategory" bind=".live" model="category" wrapper_class="col-md-12"/>
                                    </div>
                                </div>
                                <!--end col-->

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
                                <th class="sort">Amount</th>
                                <th class="sort">Description</th>
                                <th class="sort">Category</th>
                                <th class="sort">Status</th>
                                <th class="sort" data-sort="product_name">Disbursement Date</th>
                                <th class="sort" data-sort="date">Created By</th>
                                <th class="sort" data-sort="city">Created On</th>
                                <th class="sort" data-sort="city">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="list form-check-all">
                            @forelse($expenses as $expense)
                                <tr>
                                    <td class="customer_name">{{$loop->iteration}}</td>
                                    <td class="status"><span class="badge text-danger text-uppercase">{{cedi(false)}}{{$expense->total_amount}}</span></td>
                                    <td class="customer_name">{{$expense->description}}</td>
                                    <td class="date">{{$expense->category}}</td>
                                    <td class="{{$expense->status == App\Enum\ExpenseStatus::APPROVED->value ? 'text-success': 'text-warning'}}">
                                        {{$expense->status}}</td>
                                    <td class="date">{{$expense->expense_date->format('d F, Y')}}</td>
                                    <td class="date">
                                        <a class="text-info" href="{{route('staff.show', $expense->createdBy->uuid)}}">
                                            {{$expense->createdBy->fullname}}
                                        </a>
                                    </td>
                                    <td class="date">{{$expense->created_at->format('d F, Y')}}</td>

                                    <td>
                                        <x-table-actions :id="$expense->uuid" edit="expenses.edit">
                                            <li class="list-inline-item" title="Remove">
                                                <a class="text-danger d-inline-block remove-item-btn"
                                                   @click="$dispatch('open-delete-modal', {model: 'Expense',
                                                    modelId:{{ $expense->id }},recordName: 'Expense' })">
                                                    <i class="ri-delete-bin-5-fill fs-24"></i>
                                                </a>
                                            </li>
                                        </x-table-actions>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center">
                                        <x-no-result description="No Expense found"/>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                    </div>
                    <x-paginate :collection="$expenses"/>
                </div>

            </div>
        </div>
        <livewire:modal.delete-modal />
    </div>
    <!--end col-->


</div>



@section('script')
    <script src="{{ URL::asset('build/libs/list.js/list.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/ecommerce-order.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

@endsection
