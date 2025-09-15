<div>
    @section("title", 'Fees Debts')
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">

                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">Fee Debts List</h5>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex flex-wrap align-items-start gap-2">
                                <a onclick="printReceipt('{{ route('finance.debt.printout', ['query' => $query, 'student' => $student_id, 'class' => $class_id]) }}')"
                                   class="btn btn-primary" target="_blank">
                                    <i class="ri-printer-line align-bottom me-2"></i>Print Results
                                </a>
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
                                            <input wire:model.live="query" type="text" class="form-control search"
                                                   placeholder="Search something...">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>
                                    <x-input-select wire:model.live="fee_id" :options="$fees" placeholder="Filter By Fee"
                                                    key="id" value="name" id="fees" bind=".live" wrapper_class="col-sm-2"/>

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
                            <table class="table table-nowrap align-middle table-hover">
                                <thead class="text-muted table-light">
                                <tr class="text-uppercase">
                                    <th class="sort" data-sort="id">#</th>
                                    <th class="sort" data-sort="customer_name">Student Name</th>
                                    <th class="sort" data-sort="product_name">Index Number</th>
                                    <th class="sort" data-sort="product_name">Class</th>
                                    <th class="sort" data-sort="date">Fee Owing</th>
                                    <th class="sort" data-sort="date">Academic Year</th>
                                    <th class="sort" data-sort="date">Total Amount ({{cedi()}})</th>
                                    <th class="sort" data-sort="date">Paid ({{cedi()}})</th>
                                    <th class="sort" data-sort="city">Balance ({{cedi()}})</th>
                                    <th class="sort" data-sort="city">Billed Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $iteration = 1; @endphp
                                @forelse($students as $student)
                                    @forelse($student->bills as $bill)
                                        <tr  class="clickable-row" data-href="{{ route('students.show', $student->uuid) }}">
                                            <td class="customer_name">{{$iteration++}}</td>
                                            <td class="customer_name">
                                                <a class="text-info"
                                                   href="{{route('students.show', $student->uuid)}}">
                                                    {{$student->fullname}}</a>
                                            </td>
                                            <td class="date">{{$student->index_number}} </td>
                                            <td class="date">{{$student->class->name}} </td>
                                            <td class="date">{{$bill->fee->name}} </td>
                                            <td>{{$bill->term->name}}</td>
                                            <td>{{$bill->fee->amount}}</td>
                                            <td>{{$bill->payments_sum_amount_paid ?? '0,00'}}</td>
                                            <td><span class=" bg-danger-subtle text-danger text-uppercase">
                                                    {{ number_format($bill->amount - $bill->payments_sum_amount_paid, 2)}}</span></td>
                                            <x-date-field :date="$bill->created_at"/>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center">
                                                <x-no-result description="No debt found"/>
                                            </td>
                                        </tr>
                                    @endforelse

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
                        <x-paginate :collection="$students"/>
                    </div>

                </div>
            </div>

        </div>
        <!--end col-->
    </div>

    @section('script')

        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection
</div>


