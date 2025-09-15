<?php

namespace App\Livewire\Fee;

use App\Enum\BillType;
use App\Traits\BillingTrait;
use App\Traits\CacheStore;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class BillStudent extends Component
{
    use BillingTrait, CacheStore;

    public array $bill = [
        'student_id' => '',
        'class_id' => '',
        'fee_id' => '',
        'term_id' => '',
        'type' => ''
    ];


    public function selectStudent($id): void
    {
        $this->bill['student_id'] = $id;
    }

    public function create(): void
    {

        $this->validate([
            'bill.type' => ['required', Rule::in(BillType::cases())],
            'bill.student_id' => [Rule::requiredIf(fn() => $this->bill['type'] == BillType::STUDENT->value), 'int', 'exists:students,id'],
            'bill.class_id' => [Rule::requiredIf(fn() => $this->bill['type'] == BillType::CLASSROOM->value), 'int', 'exists:classrooms,id'],
            'bill.fee_id' => ['required', 'int', 'exists:fees,id'],
            'bill.term_id' => ['required', 'exists:terms,id'],
        ]);

        $fee = school()->fees()->findOrFail($this->bill['fee_id']);
        $term = school()->terms()->findOrFail($this->bill['term_id']);

        DB::beginTransaction();
        try {
            switch ($this->bill['type']) {
                case BillType::STUDENT->value;
                    $student = school()->students()->with(['bills'])->findOrFail($this->bill['student_id']);
                    $bills = $student->bills->where('fee_id', $fee->id)->where('term_id', $term->id)->pluck('fee_id')->toArray();
                    if (in_array($fee->id, $bills)) {
                        $this->addError('bill.fee_id', 'Student has been billed with this fee');
                        $this->dispatch('error', "Student already billed with this fee this term $term->name");
                        return;
                    }
                    $this->createBill($student, $fee, $term);
                    $this->dispatch('success', "Student billed successfully");
                    break;
                case BillType::CLASSROOM->value:
                    $class = school()->classes()->findOrFail($this->bill['class_id']);
                    foreach ($class->students as $student) {
                        $bills = $student->bills->where('fee_id', $fee->id)->where('term_id', $term->id)->pluck('fee_id')->toArray();
                        if (in_array($fee->id, $bills)) continue;
                        $this->createBill($student, $fee, $term);
                    }
                    $this->dispatch('success', "Class Students billed excluding those already billed with fee");
                    break;
                case BillType::MASS->value:
                    foreach (school()->students()->get() as $student) {
                        $bills = $student->bills()->where('fee_id', $fee->id)->where('term_id', $term->id)->pluck('fee_id')->toArray();
                        if (in_array($fee->id, $bills)) continue;
                        $this->createBill($student, $fee, $term);
                    }
                    $this->dispatch('success', "Students billed excluding those already billed with fee");
                    break;
                default;
            }
            DB::commit();
            $this->reset('bill');

        } catch (\Exception $exception) {
            DB::rollBack();
          //  throw $exception;
            $this->dispatch('error', 'Something went wrong');
        }

    }

    public function getInstance()
    {
        return match ($this->bill['type']) {
            BillType::STUDENT->value => school()->students()->with(['bills'])->findOrFail($this->bill['student_id']),
            BillType::CLASSROOM->value =>  school()->classes()->findOrFail($this->bill['class_id']),
            BillType::MASS->value => school()->students()->with(['bills']),
        };
    }


    public function render()
    {
        //  dd($this->getStudents(['class']));
        return view('livewire.fee.bill-student', [
            'students' => $this->getStudents(['class']),
            'classes' => $this->getClasses(),
            'fees' => $this->getFees(),
            'terms' => $this->getTerms(),
        ]);
    }
}
