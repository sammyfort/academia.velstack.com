<?php

namespace App\Livewire\Fee;

use App\Enum\TermStatus;
use App\Models\Fee;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class FeeEdit extends Component
{
    public array $fee = [
        'name' => '',
        'amount' => '',
        'term_id' => null,
        'description' => '',
    ];
    public Fee $feeModel;

    public function mount($uuid): void
    {
        $this->feeModel = school()->fees()->where('uuid', $uuid)->firstOrFail();
        $this->fee = $this->feeModel->toArray();
    }


    public function rules(): array
    {
        return [
            'fee.name' => ['required', 'string', Rule::unique('fees', 'name')
                ->where('school_id', school()->id)->ignore($this->fee['id'])],
            'fee.amount' => ['required', 'numeric'],
            'fee.term_id' => ['required', 'int', 'exists:terms,id'],
            'fee.description' => ['nullable', 'string'],
        ];
    }
    protected function getValidationAttributes(): array
    {
        return getValidationAttributesFor($this->rules());
    }


    public function update(): void
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $this->feeModel->update($this->fee);
            DB::commit();
            $this->reset();
            $this->dispatch('success', __('Fee updated !'));

        } catch (Exception $exception) {
            DB::rollBack();
            // throw $exception;
            $this->dispatch('error', __('Something went wrong!'));
        }
    }
    public function render()
    {
        return view('livewire.fee.fee-edit', [
            'terms' => school()->terms()->where('status', TermStatus::ACTIVE->value)->get(),
            'students' => school()->students()->get(),
            'classes' => school()->classes()->get()
        ]);
    }
}
