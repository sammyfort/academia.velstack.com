<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <title>Payslip</title>
    <style>
        body {
            font-size: 11px;
        }
        .page-container {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            border: 2px solid #000;
            padding: 20px;
        }
        .company-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .ghana-flag {
            width: 60px;
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .payslip-header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .employee-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        .detail-row {
            display: flex;
        }
        .detail-label {
            min-width: 150px;
            font-weight: bold;
        }
        .detail-value {
            margin-left: 10px;
        }
        .amounts-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .amounts-table th,
        .amounts-table td {
            border: 1px solid black;
            padding: 4px 8px;
        }
        .totals {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid black;
            text-align: center;
            padding-top: 5px;
        }

        .watermark {
            font-size: 70px;
            font-weight: bold;
            color: rgba(200, 200, 200, 0.2);
            user-select: none;
        }
    </style>
</head>
<body>
<div class="page-container">
    <div class="company-header">
        <h4>{{school()->name}}</h4>
        <div>{{school()->town}}</div>
        <div>{{school()->district}},{{school()->region}}</div>
    </div>

    <div class="payslip-header">
        Payslip for the period of {{$payslip->date->format('F, Y')}}
    </div>

    <div class="employee-details">
        <div>
            <div class="detail-row">
                <span class="detail-label">Employee Id:</span>
                <span class="detail-value">{{$payslip->staff->staff_id}}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Designation:</span>
                <span class="detail-value">{{$payslip->staff->designation}}</span>
            </div>
{{--            <div class="detail-row">--}}
{{--                <span class="detail-label">Days Worked:</span>--}}
{{--                <span class="detail-value">20.0</span>--}}
{{--            </div>--}}
            <div class="detail-row">
                <span class="detail-label">Bank Name, Branch:</span>
                <span class="detail-value">{{"{$payslip->staff->bank->name}, {$payslip->staff->bank->branch}"}}</span>
            </div>

        </div>
        <div>
            <div class="detail-row">
                <span class="detail-label">Name:</span>
                <span class="detail-value">{{$payslip->staff->fullname}}</span>
            </div>

{{--            <div class="detail-row">--}}
{{--                <span class="detail-label">Days Absent:</span>--}}
{{--                <span class="detail-value">0.0</span>--}}
{{--            </div>--}}
            <div class="detail-row">
                <span class="detail-label">Bank Acct/Cheque Number:</span>
                <span class="detail-value">{{$payslip->staff->bank->account_number}}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Ghana Card No:</span>
                <span class="detail-value">{{$payslip->staff->national_id}}</span>
            </div>
        </div>
    </div>
    @php

        $basic_salary = $payslip->staff->basic_salary;
        $allowances = $payslip->staff->allowancesAndDeductions()->where('type', \App\Enum\AllowanceType::ALLOWANCE->value)->get();
        $deductions = $payslip->staff->allowancesAndDeductions()->where('type', \App\Enum\AllowanceType::DEDUCTION->value)->get();
        $totalEarnings = $basic_salary + $allowances->sum('amount');
        $totalDeductions = $deductions->sum('amount');
        $netPayout = $totalEarnings - $totalDeductions;
    @endphp
    <table class="amounts-table">
        <thead>
        <tr>
            <th>Earnings</th>
            <th class="text-end">Amount</th>
            <th>Deductions</th>
            <th class="text-end">Amount</th>
        </tr>
        </thead>
        <tbody>
        @php
            // Combine Earnings and Deductions into aligned rows
            $earnings = collect([['name' => 'Basic Salary', 'amount' => $basic_salary]])
                            ->merge($allowances)
                            ->values();

            $rows = max($earnings->count(), $deductions->count());
        @endphp

        @for ($i = 0; $i < $rows; $i++)
            <tr>
                <!-- Earnings Column -->
                @if (isset($earnings[$i]))
                    <td>{{ $earnings[$i]['name'] }}</td>
                    <td class="text-end">{{ number_format($earnings[$i]['amount'], 2) }}</td>
                @else
                    <td></td>
                    <td></td>
                @endif

                <!-- Deductions Column -->
                @if (isset($deductions[$i]))
                    <td>{{ $deductions[$i]->name }}</td>
                    <td class="text-end">{{ number_format($deductions[$i]->amount, 2) }}</td>
                @else
                    <td></td>
                    <td></td>
                @endif
            </tr>
        @endfor

        <!-- Totals -->
        <tr class="totals">
            <td>Total Earnings (Rounded)</td>
            <td class="text-end">{{ number_format($totalEarnings, 2) }}</td>
            <td>Total Deductions (Rounded)</td>
            <td class="text-end">{{ number_format($totalDeductions, 2) }}</td>
        </tr>
        <tr class="totals">
            <td colspan="3">Net Pay (Rounded)</td>
            <td class="text-end">{{ number_format($netPayout, 2) }}</td>
        </tr>
        </tbody>
    </table>

    <div class="text-center small">(All figures in Ghana Cedis)</div>
    <div class="footer text-center">
        <div class="watermark ">{{str($payslip->status)->upper()}}</div>
        <p>This is a system-generated payslip.</p>

    </div>

    <div class="signatures">
        <div class="signature-line">Employer's Signature</div>
        <div class="signature-line">Employee's Signature</div>
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
</div>
</body>
</html>
