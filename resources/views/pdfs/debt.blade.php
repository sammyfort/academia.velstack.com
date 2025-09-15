@extends('pdfs.layout.app')

@section('title', 'Student Outstanding Fees Report')

@section('content')
    <div class="page-container">
        <div class="content-wrapper">
            <!-- Header -->
            <div class="text-center mb-4">
                <h2 class="fw-bold">{{$school->name}}</h2>
                <h6 >STUDENT OUTSTANDING FEES REPORT</h6>
                <h6 class="text-muted small">Generated on: {{ now()->format('d F Y - h:i A') }}</h6>
                @if($classQuery)
                    @php
                    $classroom = $school->classes()->find($classQuery)
                   @endphp
                    <h6 class="text-dark ">CLASS/LEVEL:<b> {{ $classroom->name}}</b> </h6>
                @endif

                @if($studentQuery)
                    @php
                        $std = $school->students()->find($studentQuery)
                    @endphp
                    <h6 class="text-dark"> <b>STUDENT NAME: {{ $std->fullname}}</b> </h6>
                @endif

            </div>

            <!-- Main Table -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr class="bg-grey">
                        <th class="text-center" style="width: 4%">#</th>
                        <th style="width: 20%">Student Name</th>

                        <th class="text-center" style="width: 8%">Class</th>
                        <th class="text-center" style="width: 8%">Term</th>
                        <th style="width: 15%">Fee Type</th>
                        <th class="text-center" style="width: 10%">Total (₵)</th>
                        <th class="text-center" style="width: 10%">Paid (₵)</th>
                        <th class="text-center" style="width: 10%">Balance (₵)</th>
                        <th class="text-center" style="width: 10%">Bill Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($studentsOwing as $student)
                        @foreach ($student->outstandingBills as $bill)
                            <tr>
                                <td class="text-center">{{ $loop->index + 1 }}</td>
                                <td>{{ $student->first_name }} {{ $student->last_name }}</td>

                                <td class="text-center">{{ $student->class->name }}</td>
                                <td class="text-center">{{ $bill->term->name }}</td>
                                <td>{{ $bill->fee->name }}</td>
                                <td class="text-end">{{ number_format($bill->fee->amount, 2) }}</td>
                                <td class="text-end">{{ number_format($bill->totalPaid(), 2) }}</td>
                                <td class="text-end {{ $bill->balance > 0 ? 'text-danger' : '' }}">
                                    {{ number_format($bill->balance, 2) }}
                                </td>
                                <td class="text-center">{{ $bill->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">No outstanding fees found</td>
                        </tr>
                    @endforelse
                    </tbody>
                    @if(count($studentsOwing) > 0)
                        <tfoot>
                        <tr class="bg-grey">
                            <td colspan="5" class="text-end fw-bold">Total Outstanding:</td>
                            <td class="text-end fw-bold">{{ number_format($studentsOwing->sum(function($student) {
                                return $student->outstandingBills->sum(function($bill) {
                                    return $bill->fee->amount;
                                });
                            }), 2) }}</td>
                            <td class="text-end fw-bold">{{ number_format($studentsOwing->sum(function($student) {
                                return $student->outstandingBills->sum(function($bill) {
                                    return $bill->totalPaid();
                                });
                            }), 2) }}</td>
                            <td class="text-end fw-bold text-danger">{{ number_format($studentsOwing->sum(function($student) {
                                return $student->outstandingBills->sum(function($bill) {
                                    return $bill->balance;
                                });
                            }), 2) }}</td>
                            <td></td>
                        </tr>
                        </tfoot>
                    @endif
                </table>
            </div>

            <!-- Footer -->
            <div class="mt-4 d-flex justify-content-between">
                <div class="small">
                    <p >Report generated by: <span>{{staff()->fullname}}</span></p>
                    <p>Date: {{now()->format('d F, Y')}}</p>
                </div>

            </div>
        </div>
    </div>
@endsection

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 100);
        };
        window.onafterprint = function() {

        };
    </script>
