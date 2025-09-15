<?php

namespace App\Livewire\Allowance;

use App\Enum\AllowanceType;
use App\Models\AllowanceAndDeduction;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AllowanceEdit extends Component
{

    public AllowanceAndDeduction $allowanceModel;

    public array $allowance = [
        'type' => '',
        'name' => '',
        'amount' => '',
        'calculation_type' => '',
    ];

    public function mount($uuid)
    {
        $this->allowanceModel = school()->allowancesAndDeductions()->where('uuid', $uuid)->firstOrFail();
        $this->allowance = $this->allowanceModel->toArray();
    }

    public function update(): void
    {
        $this->validate([
            'allowance.type' => ['required', Rule::in(AllowanceType::cases())],
            'allowance.calculation_type' => ['required', Rule::in(AllowanceType::cases())],
            'allowance.name' =>[ 'required'],
            'allowance.amount' => ['required', 'numeric'],
        ]);

        $exist = school()->allowancesAndDeductions()
            ->whereRaw('LOWER(name) = ?', [strtolower($this->allowance['name'])])
            ->where('id', '!=', $this->allowanceModel['id'])
            ->exists();

        if ($exist){
            $this->addError('allowance.name', "{$this->allowance['type']} already exists.");
            return;
        }

        $this->allowanceModel->update($this->allowance);
        $this->dispatch('success', "{$this->allowance['type']} created");

    }
    public function render()
    {
        return view('livewire.allowance.allowance-edit');
    }
}
