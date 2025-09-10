<?php

namespace App\Traits;

use Carbon\Carbon;

trait SalaryCalculator
{
    public function generatePayslip($date)
    {

        $startDate = Carbon::parse($date);



        $allowances = $this->allowancesAndDeductions()
            ->where('type', 'allowance')
            ->sum('amount');

        $deductions = $this->allowancesAndDeductions()
            ->where('type', 'deduction')
            ->sum('amount');


        $netSalary = $this->basic_salary + $allowances - $deductions;


       return $this->payslips()->updateOrCreate(
            [
                'date' => $startDate,
            ],
            [
                'school_id' => $this->school_id,
                'basic_salary' => $this->basic_salary,
                'allowances' => $allowances,
                'deductions' => $deductions,
                'net_salary' => $netSalary,

            ]);
    }

}
