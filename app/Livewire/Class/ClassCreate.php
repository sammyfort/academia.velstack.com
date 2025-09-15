<?php

namespace App\Livewire\Class;

use App\Enum\ClassGroup;
use App\Enum\ClassLevel;
use App\Traits\Toaster;

use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;



class ClassCreate extends Component
{
    use Toaster;

    public array $class = [
        'name' => "",
        'level' => '',
        'group' => '',
        'slug' => '',
    ];


    public function create(): void
    {
        $this->validate([
            'class.name' => ['required'],
            'class.slug' => ['nullable'],
            'class.level' => ['required', Rule::in(ClassLevel::cases())],
            'class.group' => ['required', Rule::in(ClassGroup::cases())],
        ]);

        $existingClass = school()->classes()->whereRaw('LOWER(name) = ?', [strtolower($this->class['name'])])->exists();

        if ($existingClass){
            $this->addError('class.name', 'Class already exists.');
            return;
        }
        if (empty($this->class['slug'])) $this->class['slug'] = generateString('CL', 8);

        school()->classes()->create($this->class);

        $this->dispatch('success', __('Class created successfully!'));

        $this->reset();
    }
    public function render(): View
    {
        return view('livewire.class.class-create');
    }
}
