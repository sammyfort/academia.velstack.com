<?php

namespace App\Livewire\Fee;

use App\Enum\FeeType;
use App\Enum\TermStatus;
use App\Models\Fee;
use App\Models\Term;
use App\Traits\BillingTrait;
use App\Traits\CacheStore;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;


#[Layout('layouts.app')]
class FeeCreate extends Component
{
    use BillingTrait, CacheStore;

    public array $fee = [
        'name' => '',
        'amount' => '',
        'term_id' => null,
        'description' => '',
    ];


    public function rules(): array
    {
        return [
            'fee.name' => ['required', 'string', Rule::unique('fees', 'name')
                ->where('school_id', school()->id)],
            'fee.amount' => ['required', 'numeric'],
            'fee.term_id' => ['required', 'int', 'exists:terms,id'],
            'fee.description' => ['nullable', 'string'],
        ];
    }
    protected function getValidationAttributes(): array
    {
        return getValidationAttributesFor($this->rules());
    }


    public function create(): void
    {

        $this->validate();
        DB::beginTransaction();
        try {
            school()->fees()->create($this->fee);
            DB::commit();
            $this->reset();
            $this->dispatch('success', __('Fee created successfully!'));

        } catch (Exception $exception) {
            DB::rollBack();
            // throw $exception;
            $this->dispatch('error', __('Something went wrong!'));
        }
    }

    public function bill(Fee $fee, Term $term): void
    {
        switch ($this->fee['type']) {
            case FeeType::STUDENT->value:
                $student = school()->students()->where('id', $this->fee['student_id'])->firstOrFail();
                $this->createBill($student, $fee, $term);
                break;
            case  FeeType::CLASSROOM->value:
                $class = school()->classes()->findOrFail($this->fee['class_id']);
                foreach ($class->students as $student) {
                    if ($student->hasFees($fee->id, $term->id)) continue;
                    $this->createBill($student, $fee, $term);
                }
                break;

            case FeeType::MASS->value:
                foreach (school()->students as $student) {
                    if ($student->hasFees($fee->id, $term->id)) continue;
                    $this->createBill($student, $fee, $term);
                }
                break;
            default:
        }
    }

    public function render()
    {
        return view('livewire.fee.fee-create', [
            'terms' => $this->getTerms(),
        ]);
    }
}
