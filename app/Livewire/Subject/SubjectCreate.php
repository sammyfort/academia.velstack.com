<?php

namespace App\Livewire\Subject;

use App\Enum\Subjects;
use App\Traits\Toaster;
use Illuminate\Validation\Rule;
use Livewire\Component;


class SubjectCreate extends Component
{
    use Toaster;

     public array $subject = [
         'name' => '',
         'code' => ''
     ];

     public function create(): void
     {
         $this->validate([
             'subject.name' => ['required', Rule::in(Subjects::cases()),
                 Rule::unique('subjects', 'name')
                 ->where('school_id', school()->id)],
             'subject.code' => ['nullable'],
         ]);

         if (empty($this->subject['code'])) $this->subject['code'] = generateString('SUB', 4, 'number');
         school()->subjects()->create($this->subject);
         $this->dispatch('success', 'Successfully created subject');
         $this->reset();
     }

     protected function getMessages(): array
     {
         return [
           'subject.name.unique' => 'Subject already exists',
         ];

     }




    public function render()
    {
        return view('livewire.subject.subject-create');
    }
}
