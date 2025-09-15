<div>
    @section("title", 'Overflows')
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">

                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">Payment Overflows</h5>
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



                                    <x-input-select wire:model.live="class_id" :options="$classes" placeholder="Filter By Class"
                                                    key="id" value="name" id="classes" bind=".live" wrapper_class="col-sm-2"/>

                                    <x-input-select wire:model.live="student_id" :options="$pupils" placeholder="Filter By Student"
                                                    key="id" value="fullname" id="classes" bind=".live" wrapper_class="col-sm-2"/>


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
                                    <th class="sort" data-sort="product_name">Amount</th>
                                    <th class="sort" data-sort="product_name">Description</th>
                                    <th class="sort" data-sort="city">Created On</th>
                                    <th class="sort" data-sort="city">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($students as $student)
                                    @forelse($student->surplusPayments as $overflow)
                                        <tr>
                                            <td class="customer_name">{{$loop->iteration}}</td>
                                            <td class="customer_name">{{$overflow->student->fullname}}</td>
                                            <td class="date">{{$overflow->amount}} </td>
                                            <td>
                                                {{$overflow->description}}
                                                @if(isset($overflow->payment_id))
                                                    <a target="_blank" class="text-info"
                                                        href="{{route('finance.payment.receipt', $overflow->payment->uuid)}}">
                                                        View Payment</a>
                                                @endif

                                            </td>

                                            <x-date-field :date="$overflow->created_at"/>
                                            <td>
                                                <x-table-actions>
                                                    <li class="list-inline-item" title="Remove">
                                                        <a class="text-danger d-inline-block remove-item-btn"
                                                           @click="$dispatch('open-delete-modal', {model: 'SurplusPayment',
                                                           modelId:{{ $overflow->id }},recordName: 'Payment Overflow' })">
                                                            <i class="ri-delete-bin-5-fill fs-24"></i>
                                                        </a>
                                                    </li>
                                                </x-table-actions>
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse

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
                        <x-paginate :collection="$students"/>
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


