<?php

namespace App\Livewire\Library;

use App\Enum\UserType;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;

class BookIssue extends Component
{
    public array $lend = [
        'lend_to' => '',
        'book_id' => '',
        'student_id' => '',
        'staff_id' => '',
        'due_on' => '',
    ];

    public function issue(): void
    {
        $this->validate([
            'lend.lend_to' => ['required'],
            'lend.student_id' => [Rule::requiredIf(fn() => $this->lend['lend_to'] == UserType::STUDENT->value), 'nullable', 'integer', 'exists:students,id'],
            'lend.staff_id' => [Rule::requiredIf(fn() => $this->lend['lend_to'] == UserType::STAFF->value), 'nullable', 'integer', 'exists:staff,id'],
            'lend.book_id' => ['required', 'integer', 'exists:books,id'],
            'lend.due_on' => ['required', 'date'],
        ]);

        $book = school()->books()->findOrFail($this->lend['book_id']);

        if ($book->remaining_copies <= 0) {
            $this->dispatch('error', 'No copies available for lending.');
            return;
        }

        switch ($this->lend['lend_to']) {
            case UserType::STUDENT->value:
                $student = school()->students()->findOrFail($this->lend['student_id']);
                $existingBook = $student->lendBooks()
                    ->where('book_id', $book->id)
                    ->whereNull('returned_on')
                    ->exists();

                if ($existingBook) {
                    $this->addError('lend.book_id',
                        "Book {$book->title} already lent to $student->fullname.");
                    break;
                }
                $student->lendBooks()->create([
                    'book_id' => $book->id,
                    'school_id' => $student->school_id,
                    'lent_on' => now(),
                    'due_on' => Carbon::parse($this->lend['due_on']),
                ]);
                $this->dispatch('success', "Book lent to $student->fullname");
                $this->reset('lend');
                break;
            case UserType::STAFF->value:
                $staff = school()->staff()->findOrFail($this->lend['staff_id']);
                $existingBook = $staff->lendBooks()
                    ->where('book_id', $book->id)
                    ->whereNull('returned_on')
                    ->exists();

                if ($existingBook) {
                    $this->addError('lend.book_id',
                        "Book {$book->title} already lent to $staff->fullname.");
                    break;
                }
                $staff->lendBooks()->create([
                    'book_id' => $book->id,
                    'school_id' => $staff->school_id,
                    'lent_on' => now(),
                    'due_on' => Carbon::parse($this->lend['due_on']),
                ]);
                $this->dispatch('success', "Book lent to $staff->fullname");
                $this->reset('lend');
                break;

                default;

        }


    }

    public function render()
    {
        return view('livewire.library.book-issue', [
            'students' => school()->students()->get(),
            'books' => school()->books()->get(),
            'staff' => school()->staff()->get()
        ]);
    }
}
