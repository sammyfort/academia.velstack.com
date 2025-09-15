@extends('pdfs.layout.app')

@section('title', 'Payment Receipt')

@section('content')
    <div class="page-container">
        <div class="content-wrapper">
            <!-- School Info Header -->
            <div class="text-center mb-4">
                <h3 class="fw-bold mb-1">{{$payment->school->name}}</h3>
                <h5 class="small mb-0">{{"{$payment->school->town}, {$payment->school->district}"}}</h5>
                <h5 class="small mb-0">Tel: {{$payment->school->phone}} | Email: {{$payment->school->email}}</h5>
            </div>

            <!-- Receipt Title -->
            <div class="text-center mb-4">
                <h4 class="fw-bold">OFFICIAL RECEIPT</h4>
                <p class="small text-muted mb-0">Receipt No: {{$payment->uuid}}</p>
            </div>

            <!-- Student & Payment Info -->
            <div class="row mb-4">
                <div class="col-6">
                    <table class="table table-borderless">
                        <tr>
                            <td style="width: 40%"><strong>Student Name:</strong></td>
                            <td>{{$payment->student->fullname}}</td>
                        </tr>
                        <tr>
                            <td><strong>Index Number:</strong></td>
                            <td>{{$payment->student->index_number}}</td>
                        </tr>
                        <tr>
                            <td><strong>Class:</strong></td>
                            <td>{{$payment->student->class->name}}</td>
                        </tr>
                        <tr>
                            <td><strong>Amount :</strong></td>
                            <td>{{$payment->amount}}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-6">
                    <table class="table table-borderless">
                        <tr>
                            <td style="width: 40%"><strong>Payment Date:</strong></td>
                            <td>{{$payment->created_at->format('d/m/y')}}</td>
                        </tr>
                        <tr>
                            <td><strong>Payer's Name:</strong></td>
                            <td>{{$payment->payer_name}}</td>
                        </tr>
                        <tr>
                            <td><strong>Payer's Phone:</strong></td>
                            <td>{{$payment->payer_phone}}</td>
                        </tr>
                        <tr>
                            <td><strong>Payment Mode:</strong></td>
                            <td>{{$payment->channel}}</td>
                        </tr>

                    </table>
                </div>
            </div>

            <!-- Payment Details Table -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead>
                    <tr class="bg-grey">
                        <th class="text-center" style="width: 5%">#</th>
                        <th style="width: 45%">Fee Description</th>
                        <th class="text-center" style="width: 25%">Term</th>
                        <th class="text-center" style="width: 25%">Fee Amount (₵)</th>
                        <th class="text-center" style="width: 25%">Paid Amount (₵)</th>
                        <th class="text-center" style="width: 25%">Balance (₵)</th>
                    </tr>
                    </thead>
                    @php

                        $totalPaid = $payment->bills->sum(function ($paymentBill) {
                            return $paymentBill->bill->totalPaid();
                        });

                        $totalBillsAmount = $payment->bills->sum(function ($paymentBill) {
                            return $paymentBill->bill->fee->amount;
                        });

                        $outstandingBalance = $totalBillsAmount - $totalPaid;
                    @endphp
                    <tbody>

                    @foreach($payment->bills as $paymentBill)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td>{{$paymentBill->bill->fee->name}}</td>
                            <td>{{$paymentBill->bill->term->name}}</td>
                            <td class="text-end">{{$paymentBill->bill->fee->amount}}</td>
                            <td class="text-end">{{$paymentBill->bill->totalPaid()}}</td>
                            <td class="text-end">{{$paymentBill->bill->balance}}</td>
                        </tr>
                    @endforeach

                    </tbody>
                    <tfoot>
                    <tr class="bg-grey">
                        <td colspan="3" class="text-end fw-bold">Total:</td>
                        <td class="text-end fw-bold">{{$totalBillsAmount}}</td>
                        <td class="text-end fw-bold">{{$totalPaid}}</td>
                        <td class="text-end text-danger">{{$outstandingBalance}}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Outstanding Balance:</td>
                        <td colspan="2" class="text-end fw-bold text-danger">{{$outstandingBalance}}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Amount in Words -->
            <div class="border p-2 mb-4">
                <strong>Amount Paid in Words:</strong> {{str(\Illuminate\Support\Number::spellOrdinal($payment->amount))->upper()}} GHANA CEDIS ONLY
            </div>

            <!-- Footer with Signatures -->
            <div class="row mt-5">
                <div class="col-4">
                    <div class="text-center">
                        <div class=" mb-1" style="font-size: 24px;"> {{$payment->payer_name}}</div>
                        <div style="border-top: 1px solid black; padding-top: 5px;">
                            <p class="mb-0"><strong>Paid by</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center">
                        <div class="mb-4">&nbsp;</div>
                        <div style="border-top: 1px solid black; padding-top: 5px;">
                            <p class="mb-0"><strong>Official Stamp</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center">
                        <div class=" mb-1" style="font-size: 24px;"> {{$payment->createdBy->fullname}}</div>
                        <div style="border-top: 1px solid black; padding-top: 5px;">
                            <p class="mb-0"><strong>Received by</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="mt-4">
                <p class="small text-muted mb-0">1. This receipt is valid subject to realization of cheque/DD/Bank Transfer</p>
                <p class="small text-muted mb-0">2. All fees once paid are non-refundable</p>
                <p class="small text-muted">3. Please keep this receipt safely for future reference</p>
            </div>
        </div>
    </div>
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 100);
        };
        window.onafterprint = function() {
            // window.location.href = '/previous-page';
        };
    </script>
@endsection
