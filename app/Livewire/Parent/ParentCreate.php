<?php

namespace App\Livewire\Parent;

use App\Rules\ValidGhanaCard;
use App\Services\ParentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ParentCreate extends Component
{
    public array $parent = [
        'name' => '',
        'email' => '',
        'phone' => '',
        'address' => '',
        'identity_number' => '',
        'occupation' => '',
    ];
    public function create(): void
    {
        $this->validate([
            'parent.name' => ['required'],
            'parent.email' => ['required', Rule::unique('__parents', 'email')],
            'parent.phone' => ['required', Rule::unique('__parents', 'phone')],
            'parent.address' => ['required'],
            'parent.identity_number' => ['required', new ValidGhanaCard()],
            'parent.occupation' => ['required', 'string'],
        ]);

        DB::transaction(function () {
            (new ParentService())->create($this->parent);

        });
        $this->dispatch('success', 'Parent has been created');
        $this->reset();
    }
    public function render()
    {
        return view('livewire.parent.parent-create');
    }
}
