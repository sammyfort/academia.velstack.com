<?php

namespace App\Livewire\Parent;

use App\Models\Student;
use Livewire\Component;

class ParentStudent extends Component
{
    public Student $student;

    public string $search = '';
    public int $paginate = 10;

    public bool $show_parents = false;

    public function setDisplay(bool $value): void
    {
        $this->show_parents = $value;

    }
    public function resetFilter(): void
    {
        $this->search = '';
        $this->paginate = 10;
    }

    public function mount($uuid): void
    {
        $this->student = parent()->students()->where('students.uuid', $uuid)->firstOrFail();
    }


    public function render()
    {
        return view('livewire.student.student-show', [
            'payments' => $this->student->payments()->search($this->search)->paginate($this->paginate),
            'debts' => $this->student->outstandingBills()->search($this->search)->paginate($this->paginate),
            'bills' => $this->student->bills()->search($this->search)->paginate($this->paginate),
        ]);
    }
}
