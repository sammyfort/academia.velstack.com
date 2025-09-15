<?php

namespace App\Livewire\Subject;

use App\Models\_Parent;
use App\Models\Subject;
use Livewire\Component;

class SubjectEdit extends Component
{
    public array $edit = [
        'code' => '',

    ];

    public Subject $subject;


    public function mount($uuid): void
    {
        $this->subject = school()->subjects()->where('uuid', $uuid)->firstOrFail();
        $this->edit = $this->subject->toArray();
    }

    public function update(): void
    {
        $this->validate([
            'edit.code' => ['nullable'],
        ]);
        $this->edit['code'] = $this->edit['code'] ?? $this->subject->code;
        $this->subject->update($this->edit);
        $this->dispatch('success', 'Subject updated');
    }
    public function render()
    {
        return view('livewire.subject.subject-edit');
    }
}
