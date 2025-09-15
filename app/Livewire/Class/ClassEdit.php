<?php

namespace App\Livewire\Class;

use App\Enum\ClassGroup;
use App\Enum\ClassLevel;
use App\Models\Classroom;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ClassEdit extends Component
{
    public array $edit = [
        'name' => '',
        'slug' => '',
        'level' => '',
        'group' => '',
    ];

    public Classroom $class;

    public function mount($uuid): void
    {
        $class = school()->classes()->where('uuid', $uuid)->firstOrFail();
        $this->class = $class;
        $this->edit = $class->toArray();

    }

    public function update(): void
    {
        $this->validate([
            'edit.name' => ['required',],
            'edit.slug' => ['required'],
            'edit.level' => ['required', Rule::in(ClassLevel::cases())],
            'edit.group' => ['required', Rule::in(ClassGroup::cases())],
        ]);
        $existingClass = school()
            ->classes()
            ->whereRaw('LOWER(name) = ?', [strtolower($this->class['name'])])
            ->where('id', '!=', $this->class['id'])
            ->exists();

        if ($existingClass) {
            $this->addError('edit.name', 'Class already exists.');
            return;
        }
        $this->class->update($this->edit);
        $this->dispatch('success', 'Class updated successfully');

    }
    public function render()
    {
        return view('livewire.class.class-edit');
    }
}
