<?php

namespace App\Livewire\Allowance;

use App\Enum\AllowanceCalculationType;
use App\Enum\AllowanceType;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AllowanceCreate extends Component
{
    public array $allowance = [
        'type' => '',
        'name' => '',
        'amount' => '',
        'calculation_type' => '',
    ];

    public function create(): void
    {
        $this->validate([
            'allowance.type' => ['required', Rule::in(AllowanceType::cases())],
            'allowance.calculation_type' => ['required', Rule::in(AllowanceCalculationType::cases())],
            'allowance.name' =>[ 'required'],
            'allowance.amount' => ['required', 'numeric'],
        ]);

        $exist = school()->allowancesAndDeductions()->whereRaw('LOWER(name) = ?', [strtolower($this->allowance['name'])])->exists();

        if ($exist){
            $this->addError('allowance.name', "{$this->allowance['type']} already exists.");
            return;
        }

        school()->allowancesAndDeductions()->create($this->allowance);
        $this->dispatch('success', "{$this->allowance['type']} created");
        $this->reset('allowance');
    }

    public function render()
    {
        return view('livewire.allowance.allowance-create');
    }
}
