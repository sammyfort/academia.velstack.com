<?php

namespace App\Livewire\FinancialReport;

use App\Enum\ExpenseStatus;
use App\Exports\Financial\ReportExport;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportIndex extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $start_date;
    public $end_date;
    public array|Collection $expenses;
    public array|Collection $payments;
    public string $note = "";
    public int|float $total_payments = 0;
    public int|float $total_expenses = 0;
    public int|float $net_balance = 0;

    public function mount(): void
    {
        $this->start_date = now()->startOfMonth()->startOfDay();
        $this->end_date = now()->endOfMonth()->endOfDay();
        $this->fetchReports();
    }

    public function updated($propertyName): void
    {
        if (in_array($propertyName, ['start_date', 'end_date'])) {
            $this->start_date = Carbon::parse($this->start_date)->startOfDay();
            $this->end_date = Carbon::parse($this->end_date)->endOfDay();
            $this->fetchReports();
        }
    }

    #[On('dateUpdated')]
    public function dateUpdated($startDate, $endDate): void
    {
        $this->start_date = Carbon::parse($startDate)->startOfDay();
        $this->end_date = Carbon::parse($endDate)->endOfDay();
        $this->fetchReports();
    }
    public function exportReports(): ? BinaryFileResponse
    {
        if ($this->start_date and $this->end_date) {
           return  Excel::download(new ReportExport($this->payments, $this->expenses),
                 "$this->start_date-to-$this->end_date-report.xlsx");

        }
        $this->dispatch('error', "Please select date range");

        return null;
    }

    public function fetchReports(): void
    {
        $this->payments = school()->payments()
            ->whereBetween('created_at', [$this->start_date, $this->end_date])
            ->get();

        $this->expenses = school()->expenses()
            ->whereBetween('expense_date', [$this->start_date, $this->end_date])
            ->where('status', ExpenseStatus::APPROVED->value)
            ->get();

        $this->total_payments = $this->payments->sum(function ($payment) {
            return $payment->amount;
        });
        $this->total_expenses = $this->expenses->sum('amount');


        $this->net_balance = $this->total_payments - $this->total_expenses;

        $this->dispatch('filterByDate', $this->start_date, $this->end_date);
    }


    /**
     * Get chart data for sales & expenses
     *
     * @return array
     */
    public function getChartData(): array
    {
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        $dates = collect();
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dates->put($date->toDateString(), ['payments' => 0, 'expenses' => 0]);
        }

        $paymentData = school()->payments()
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total_payment')
            ->groupBy('date')
            ->pluck('total_payment', 'date');

        $expenseData =  school()->expenses()
            ->whereBetween('expense_date', [$start, $end])
            ->where('status', ExpenseStatus::APPROVED->value)
            ->selectRaw('DATE(expense_date) as date, SUM(amount) as total_expenses')
            ->groupBy('date')
            ->pluck('total_expenses', 'date');

        foreach ($dates->keys() as $date) {
            $dates->put($date, [
                'payments' => $paymentData[$date] ?? 0,
                'expenses' => $expenseData[$date] ?? 0,
            ]);
        }


        return [
            'dates' => $dates->keys()->toArray(),
            'payments' => $dates->pluck('payments')->toArray(),
            'expenses' => $dates->pluck('expenses')->toArray(),
        ];
    }

    public function paymentsChartData(): LineChartModel
    {
        $data = $this->getChartData();
        $paymentChart = (new LineChartModel())
            ->setTitle('Payments')
            ->setAnimated(true)
            ->setSmoothCurve();

        foreach ($data['dates'] as $index => $date) {
            $date = Carbon::parse($date)->format('d M Y');
            $paymentChart->addPoint($date, $data['payments'][$index], 'Payments');
        }

        $paymentChart->setJsonConfig([
            'colors' => ['#1ea001'],
            'markers' => [
                'size' => 5,
                'strokeWidth' => 2,
                'colors' => ['#e5cb06'],
                'hover' => [
                    'size' => 6,
                ],
            ],
        ]);

        return $paymentChart;
    }

    public function expenseChartData(): LineChartModel
    {
        $data = $this->getChartData();
        $expenseChart = $lineChartModel = (new LineChartModel())
            ->setTitle('Expense')
            ->setSmoothCurve()
            ->setColors('#038103');

        foreach ($data['dates'] as $index => $date) {
            $date = Carbon::parse($date)->format('d M Y');
            $lineChartModel->addPoint($date, $data['expenses'][$index], 'Expenses');
        }

        $expenseChart->setJsonConfig([
            'markers' => [
                'size' => 5,
                'strokeWidth' => 2,
                'colors' => ['#ff0303'],
                'hover' => [
                    'size' => 6,
                ],
            ],
        ]);
        return $expenseChart;
    }

    #[On('dateUpdated')]
    public function render()
    {

        return view('livewire.financial-report.report-index', [
            'paymentsChart' => $this->paymentsChartData(),
            'expenseChart' => $this->expenseChartData()
        ]);
    }
}
