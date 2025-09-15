<div>
    @section("title", 'Financial Report')
    <div class="row"  x-data="page">

        <div class="row">
            <div class="col-lg-3">
                <div class="card bg-success">
                    <div class="card-body d-flex gap-3 align-items-center">

                        <div class="flex-grow-1">
                            <h5 class="fs-15 text-white">GHS {{number_format($total_payments, 2)}}</h5>
                            <p class="mb-0 text-white">Total Payment Income</p>
                        </div>
                    </div>
                </div>
            </div><!--end col-->
            <div class="col-lg-3">
                <div class="card bg-danger">
                    <div class="card-body d-flex gap-3 align-items-center">

                        <div class="flex-grow-1">
                            <h5 class="fs-15 text-white">GHS {{number_format($total_expenses, 2)}}</h5>
                            <p class="mb-0 text-white">Total Expenses</p>
                        </div>
                    </div>
                </div>
            </div><!--end col-->
            <div class="col-lg-3">
                <div class="card bg-dark">
                    <div class="card-body d-flex gap-3 align-items-center">

                        <div class="flex-grow-1">
                            <h5 class="fs-15 text-white">GHS {{number_format($net_balance, 2) }}</h5>
                            <p class="mb-0 text-white">Net Balance</p>
                        </div>
                    </div>
                </div>
            </div><!--end col-->
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body d-flex gap-3 align-items-center">

                        <div class="flex-grow-1">
                            <h5 class="fs-15">{{cedi(false)}} {{number_format($net_balance, 2) }}</h5>
                            <p class="mb-0 text-muted">Ending Balance</p>
                        </div>
                    </div>
                </div>
            </div><!--end col-->

        </div><!--end row-->


        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <!-- Financial Report Title -->
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">Financial Report List</h5>
                            </div>
                        </div>

                        <!-- From Date -->
                        <div class="col-sm-2 d-flex align-items-center">
                            <label for="fromDate" class="form-label me-2">From:</label>
                            <div id="report-date" class="search-box flatpickr flatpickr-date flex-grow-1">
                                <input
                                    id="fromDate"
                                    wire:model.live="start_date"
                                    type="date"
                                    data-provider="flatpickr"
                                    class="form-control flatpickr-input"
                                    wire:ignore
                                    placeholder="From Date">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>

                        <!-- To Date -->
                        <div class="col-sm-2 d-flex align-items-center">
                            <label for="toDate" class="form-label me-2">To:</label>
                            <div class="search-box flatpickr flatpickr-date flex-grow-1">
                                <input
                                    id="toDate"
                                    wire:model.live="end_date"
                                    type="date"
                                    data-provider="flatpickr"
                                    class="form-control flatpickr-input"
                                    wire:ignore
                                    placeholder="To Date">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>

                        <!-- Generate Report Button -->
                        <div class="col-sm-2">
                            <div>
                                <a href="{{route('finance.report.print', ['startDate' => $start_date, 'endDate' => $end_date, 'note' => $note])}}"
                                   class="btn btn-primary w-100"
                                      target="_blank">
                                    <i class="ri-printer-fill me-2 align-bottom"></i>Generate Report
                                </a>

                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div>
                                <button wire:click="exportReports" class="btn btn-success add-btn">
                                    <i class="ri-file-excel-2-fill align-bottom me-1"></i> Export
                                </button>

                            </div>
                        </div>

                        <div class="col-sm-6 d-flex align-items-center">
                            <div class="search-box  flex-grow-1" wire:ignore>
                                    <x-input-text
                                        type="textarea"
                                        model="note"
                                        bind=".live"
                                        class="form-control"
                                        placeholder="Add note to this report"
                                    />
                            </div>
                        </div>

                    </div>
                </div>
            </div>


        </div>

    </div>
    <!-- end row-->


    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">

                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">Payments</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div style="height: 32rem;" >
                        <livewire:livewire-line-chart
                            key="{{ $paymentsChart->reactiveKey()}}"
                            :line-chart-model="$paymentsChart"
                        />
                    </div>

                </div>
            </div>
        </div>
        <!--end col-->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">

                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">Expenses</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div style="height: 32rem;" >
                        <livewire:livewire-line-chart
                            key="{{ $expenseChart->reactiveKey() }}"
                            :line-chart-model="$expenseChart"
                        />
                    </div>

                </div>
            </div>
        </div>
        <!--end col-->
    </div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('page', () => ({
            init() {
                // Initialize Flatpickr with Range Mode
                flatpickr("#report-date", {
                    wrap: true,
                    dateFormat: "d-m-Y",
                    mode: "range",
                    maxDate: moment().format('DD-MM-YYYY'),
                    onChange: (selectedDates) => {
                        let startDate = selectedDates[0];
                        let endDate = selectedDates[1];

                        if (startDate && endDate) {
                            // Dispatch a Livewire event to update the data
                            Livewire.emit('dateUpdated', {
                                startDate: startDate,
                                endDate: endDate,
                            });
                        }
                    },
                });
            },
            scrollToDiv(divId) {
                const targetDiv = document.getElementById(divId);
                if (targetDiv) {
                    targetDiv.scrollIntoView({ behavior: 'smooth' });
                }
            }
        }));
    });

</script>

    @section('script')

        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection
</div>



