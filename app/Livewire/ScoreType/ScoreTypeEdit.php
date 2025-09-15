<?php

namespace App\Livewire\ScoreType;

use App\Enum\ClassRole;
use App\Models\Classroom;
use App\Models\ScoreType;
use Livewire\Component;

class ScoreTypeEdit extends Component
{
    public Classroom $class;
    public ScoreType $type;

    public array $scoreType = [
        'name' => '',
        'percentage' => ''
    ];

    public function mount($class_uuid, $scoretype_uuid): void
    {
        $this->class = school()->classes()->where('uuid', $class_uuid)->firstOrFail();
        $this->type = $this->class->scoreTypes()->where('uuid', $scoretype_uuid)->firstOrFail();
        $this->scoreType = $this->type->toArray();
        !permittedTo($this->class, ClassRole::CLASS_TEACHER->value, null, true);
    }

    public function update(): void
    {
        $this->validate([
            'scoreType.name' => ['required', 'string'],
            'scoreType.percentage' => ['required', 'numeric'],
        ]);

        $this->type->update($this->scoreType);
        $this->dispatch('success', __('Classroom score type updated successfully!'));
    }

    public function render()
    {
        return view('livewire.score-type.score-type-edit');
    }
}
