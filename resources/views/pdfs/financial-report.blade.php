@extends('pdfs.layout.app')

@section('title', 'Financial Report')

@section('content')
    <div class="page-container">
        <div class="content-wrapper">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex align-items-center">
                        <div style="width: 50px; height: 50px">
                            <div style="width: 100%; height: 100%; background: #666; border-radius: 50% 0 0 50%"></div>
                        </div>
                        <h2 class="ms-3 mb-0 " style="font-size: 24px;"><b>{{school()->name}}</b></h2> <hr>

                    </div>
                </div>
            </div>
            <hr>
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="ms-3 mb-0" style="font-size: 24px;">Financial Report</h2>

                </div>

                    <div class="col-12 small text-center"><b>Generated on: {{now()->format('d F, Y: h:i:sA')}}</b></div>

            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <table class="table table-sm">
                        <tr>
                            <td class="bg-grey" style="width: 120px">Date</td>
                            <td> <b>{{$startDate?->format('F d, Y')}} </b> To <b>{{$endDate?->format('F d, Y')}}</b></td>
                        </tr>
                    </table>
                </div>
                <div class="col-6">
                    <table class="table table-sm">
                        <tr>
                            <td class="bg-grey" style="width: 120px">Prepared By</td>
                            <td>{{staff()->fullname}}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <table class="table table-sm mb-4">
                <tr>
                    <td class="bg-grey">Beginning Balance</td>
                    <td class="text-end" style="width: 150px">{{cedi()}}{{$total_payments}}</td>
                </tr>
            </table>

            <h6 class="mb-2">Payment Income</h6>
            <table class="table table-sm mb-4">
{{--                <thead>--}}
{{--                <tr class="bg-grey">--}}
{{--                    <th style="width: 120px">Date</th>--}}
{{--                    <th>Description</th>--}}
{{--                    <th>Channel</th>--}}
{{--                    <th class="text-end" style="width: 150px">Amount ({{cedi()}})</th>--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}
{{--                @forelse($payments as $payment)--}}
{{--                    <tr>--}}
{{--                        <td>{{$payment->created_at->format('d/m/Y')}}</td>--}}
{{--                        <td>{{ucwords($payment->description ?? 'Bill Payment')}}</td>--}}
{{--                        <td>{{ucwords($payment->channel)}}</td>--}}
{{--                        <td class="text-end">{{$payment->amount}}</td>--}}
{{--                    </tr>--}}
{{--                    @empty--}}
{{--                    <tr>--}}
{{--                        <td colspan="12" class="text-center">No Payment found for the selected date range</td>--}}
{{--                    </tr>--}}
{{--                @endforelse--}}
                <tr class="fw-bold">
                    <td colspan="3">Total Income:</td>
                    <td class="text-end">{{cedi()}} {{$total_payments}}</td>
                </tr>
                </tbody>
            </table>

            <h6 class="mb-2">Expenses</h6>
            <table class="table table-sm mb-4">
                <thead>
                <tr class="bg-grey">
                    <th style="width: 120px">Date</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th class="text-end" style="width: 150px">Amount ({{cedi()}})</th>
                </tr>
                </thead>
                <tbody>
                @forelse($expenses as $expense)
                    <tr>
                        <td>{{$expense->created_at->format('d/m/Y')}}</td>
                        <td>{{$expense->description}}</td>
                        <td>{{$expense->category}}</td>
                        <td class="text-end">{{$expense->amount}}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center">No Expense found for the selected date range</td>
                    </tr>
                @endforelse

                <tr class="fw-bold">
                    <td colspan="3">Total Expenses:</td>
                    <td class="text-end">{{cedi()}} {{$total_expenses}}</td>
                </tr>
                </tbody>
            </table>


            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="mb-2">Net Income (Loss)</h6>
                    <div class="d-flex justify-content-start gap-5">
                        <div>
                            <div class="fw-bold">{{cedi()}}{{$total_payments}}</div>
                            <div class="small">Total Income</div>
                        </div>
                        <div>
                            <div class="fw-bold">{{cedi()}}{{$total_expenses}}</div>
                            <div class="small">Total Expenses</div>
                        </div>
                        <div>
                            <div class="fw-bold">{{cedi()}}{{$net_balance}}</div>
                            <div class="small">Net Income (Loss)</div>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-sm mb-4">
                <tr>
                    <td class="bg-grey">Ending Balance</td>
                    <td class="text-end" style="width: 150px">{{cedi()}}{{$net_balance}}</td>
                </tr>
            </table>

            @if(isset($note))
                <div class="mt-4">
                    <h6 class="mb-2">Additional Notes</h6>
                    <ul class="ps-4">
                        <li>{{$note}}</li>
                    </ul>
                </div>
            @endif

        </div>
    </div>
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 100);
        };
        window.onafterprint = function() {
          //  window.location.href = "{{route('finance.report')}}";
        };
    </script>
@endsection


