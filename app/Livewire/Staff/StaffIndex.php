<?php

namespace App\Livewire\Staff;

use App\Enum\PayslipStatus;
use App\Models\Payslip;
use App\Models\School;
use App\Models\Staff;
use App\Models\Term;
use App\Services\DataTable;
use App\Traits\CacheStore;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;


class StaffIndex extends Component
{
    use WithPagination, WithoutUrlPagination, CacheStore;
    public string $search = "";
    public int $paginate = 10;
    public Date|string $date;
    public Term $term;
    public ?int $term_id = null;
    public School $school;

    // attendance sheet
    public ?Staff $staffModel;
    public array|Collection $attendances = [];
    public bool $showTimeSheet = false;
    public function openTimeSheet($staff_id): void
    {
        $this->closeTimeSheet();
        $this->staffModel = $this->school->staff()->findOrFail($staff_id);
        $this->attendances = $this->staffModel->attendances()
            ->where('term_id', $this->term_id)
            ->get();
        $this->showTimeSheet = true;
    }

    public function closeTimeSheet(): void
    {
        $this->staffModel = null;
        $this->attendances = [];
        $this->showTimeSheet = false;
    }

    public function updatedTermId($value): void
    {
        if ($value){
            $this->term = $this->school->terms()->findOrFail($value);
        }
    }

    public function resetFilter(): void
    {
        $this->search = "";
        $this->paginate = 10;
    }

    public function mount(): void
    {
        $this->date = now()->format('Y-m-d');
        $this->term = currentTerm();
        $this->term_id = $this->term->id;
        $this->school = school(['staff', 'allowancesAndDeductions']);
    }

    public function syncStaffToAllowanceAndDeductions(): void
    {
        DB::beginTransaction();
        try {
            $staff = $this->school->staff;
            $allowancesAndDeductions = $this->school->allowancesAndDeductions;
            foreach ($staff as $worker) {
                foreach ($allowancesAndDeductions as $allowance) {
                    if (!$worker->allowancesAndDeductions()->where('allowance_id', $allowance->id)->exists()) {
                        $worker->allowancesAndDeductions()->attach($allowance->id, [
                            'uuid' => Str::uuid(),
                            'school_id' => $worker->school_id,
                        ]);
                    }
                }
            }
            DB::commit();
            $this->dispatch('success', 'Successfully subjects');
            $this->dispatch('allowancesAndDeductions');
        } catch (Exception $exception) {
            DB::rollBack();
            $this->dispatch('error', $exception->getMessage());
        }
    }

    public function setPayslipStatus(Payslip $payslip, $status): void
    {
        $payslip->update(['status' => PayslipStatus::tryFrom($status)]);
        $this->dispatch('success', __('Payslip updated!'));
    }

    public function markAllAs($status = PayslipStatus::PAID->value): void
    {
        $date = Carbon::parse($this->date);
        $payslips = $this->school->payslips()
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->get();

        collect($payslips)->each(function ($payslip) use ($status) {
            $payslip->update(['status' => $status]);
        });
        $this->dispatch('success', __("Payslip marked as $status!"));

    }
    public function removeAllowance($staff_id, $allowance_id): void
    {
        $staff = $this->school->staff()->findOrFail($staff_id);
        $staff->allowancesAndDeductions()->detach($allowance_id);
        $this->dispatch('success', 'Deduction Removed');
        $this->dispatch('deductionRemoved');

    }

    public function removeDeduction($staff_id, $allowance_id): void
    {
        $staff = $this->school->staff()->findOrFail($staff_id);
        $staff->allowancesAndDeductions()->detach($allowance_id);
        $this->dispatch('success', 'Deduction Removed');
        $this->dispatch('deductionRemoved');
    }

    public function recordAttendance(int $staff_id,  $present = true): void
    {
        $staff = $this->school->staff()->findOrFail($staff_id);
        $term = $staff->school->terms()->findOrFail($this->term_id);
        recordAttendance($staff, $this->date, $term, $present);
        $this->dispatch('success', __('Staff attendance recorded!'));
    }

    #[On('recordDeleted')]
    public function render(): View
    {
        return view('livewire.staff.staff-index', [
            'staff' => (new DataTable(new Staff()))
                ->query(function ($query) {
                    $query->where('school_id', \school()->id);
                })->searchable($this->search)
                ->with(['attendances','payslips', 'allowancesAndDeductions',])
                ->latest()

                ->paginate($this->paginate),
               'terms' => $this->getTerms(),

        ]);
    }
}
