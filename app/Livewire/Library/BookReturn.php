<?php

namespace App\Livewire\Library;

use App\Enum\UserType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;

class BookReturn extends Component
{
    public array $return = [
        'from' => '',
        'student_id' => '',
        'staff_id' => '',
        'book_id' => '',
    ];

    public array|Collection $lentBooks = [];

    public function updatedReturnStudentId($value): void
    {
        if ($this->return['from']) {
            $student = school()->students()->findOrFail($value);
            $this->lentBooks = $student->lendBooks()->whereNull('returned_on')->get();
        }

    }

    public function updatedReturnStaffId($value): void
    {
        if ($this->return['from']) {
            $staff = school()->staff()->findOrFail($value);
            $this->lentBooks = $staff->lendBooks()->whereNull('returned_on')->get();
        }

    }

    public function returnBook(): void
    {
        $this->validate([
            'return.from' => ['required', Rule::in(UserType::cases())],
            'return.student_id' => [Rule::requiredIf(fn() => $this->return['from'] == UserType::STUDENT->value), 'nullable', 'integer', 'exists:students,id'],
            'return.staff_id' => [Rule::requiredIf(fn() => $this->return['from'] == UserType::STAFF->value), 'nullable', 'integer', 'exists:staff,id'],
            'return.book_id' => ['required', 'integer', 'exists:books,id'],
        ]);

        switch ($this->return['from']) {
            case UserType::STUDENT->value:
                $student = school()->students()->findOrFail($this->return['student_id']);
                $lentBook = $student->lendBooks()
                    ->where('book_id', $this->return['book_id'])
                    ->firstOrFail();
                $lentBook->update(['returned_on' => now()]);
                $this->dispatch('success', "Book returned from $student->fullname");
                $this->reset('return');
                break;
            case UserType::STAFF->value:
                $staff = school()->staff()->findOrFail($this->return['staff_id']);
                $lentBook = $staff->lendBooks()
                    ->where('book_id', $this->return['book_id'])
                    ->firstOrFail();
                $lentBook->update(['returned_on' => now()]);
                $this->dispatch('success', "Book returned from $staff->fullname");
                $this->reset('return');


        }


    }

    public function render()
    {
        return view('livewire.library.book-return', [
            'students' => school()->students()->get(),
            'books' => school()->books()->get(),
            'staff' => school()->staff()->get()
        ]);
    }
}
