<?php

namespace App\Livewire\Parent;

use App\Models\_Parent;
use App\Rules\ValidGhanaCard;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ParentEdit extends Component
{
    public array $edit = [
        'name' => '',
        'email' => '',
        'phone' => '',
        'address' => '',
        'identity_number' => '',
        'occupation' => '',
    ];

    public _Parent $parent;


    public function mount($uuid): void
    {
        $this->parent = school()->parents()->where('uuid', $uuid)->firstOrFail();
        $this->edit = $this->parent->toArray();
    }

    public function update(): void
    {
        $this->validate([
            'edit.name' => ['required'],
            'edit.email' => ['required', Rule::unique('__parents', 'email')->ignore($this->parent->id), 'email'],
            'edit.phone' => ['required',  Rule::unique('__parents', 'phone')->ignore($this->parent->id)],
            'edit.address' => ['required'],
            'edit.identity_number' => ['required', new ValidGhanaCard()],
            'edit.occupation' => ['required', 'string'],
        ]);

        $this->parent->update($this->edit);
        $this->dispatch('success', 'Parent updated');
    }

    public function render()
    {
        return view('livewire.parent.parent-edit');
    }
}
