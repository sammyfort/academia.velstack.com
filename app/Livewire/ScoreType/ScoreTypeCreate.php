<?php

namespace App\Livewire\ScoreType;

use App\Enum\ClassRole;
use App\Models\Classroom;
use Livewire\Component;

class ScoreTypeCreate extends Component
{
    public Classroom $class;

    public array $scoreType = [
        'name' => '',
        'percentage' => ''
    ];

    public function mount($class_uuid): void
    {
        $this->class = school()->classes()->where('uuid', $class_uuid)->firstOrFail();
        !permittedTo($this->class, ClassRole::CLASS_TEACHER->value, null, true);
    }

    public function create(): void
    {
        $this->validate([
            'scoreType.name' => ['required', 'string'],
            'scoreType.percentage' => ['required', 'numeric'],
        ]);
        if ($this->class->scoreTypes()->where('name', strtoupper($this->scoreType['name']))->exists()) {
            $this->addError('scoreType.name', 'The score type already exists for this class.');
            return;
        }
        $this->scoreType['school_id'] = school()->id;
        $this->class->scoreTypes()->create($this->scoreType);
        $this->scoreType['name'] = '';
        $this->scoreType['percentage'] = '';
        $this->dispatch('success', __('Classroom score type added successfully!'));
    }

    public function render()
    {
        return view('livewire.score-type.score-type-create');
    }
}
