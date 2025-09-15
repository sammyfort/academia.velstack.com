<?php

namespace App\Livewire\Library;

use App\Models\Book;
use App\Models\LentBook;
use App\Services\DataTable;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class BookLentHistory extends Component
{
    use WithPagination, WithoutUrlPagination;
    public Book $book;

    public string $search = "";
    public int $paginate = 10;
    public string $direction = 'desc';

    public function resetFilter(): void
    {
        $this->search = "";
        $this->paginate = 15;
        $this->direction = 'desc';
    }

    public function mount($uuid): void
    {
        $this->book = school()->books()->where('uuid', $uuid)->firstOrFail();

    }

    public function markAsReturned($book_id, $student_id): void
    {
        $student = school()->students()->findOrFail($student_id);
        $lentBook = school()->lendBooks()
            ->where('book_id', $book_id)
            ->where('student_id', $student->id)
            ->firstOrFail();
        $lentBook->update(['returned_on' => now()]);
        $this->dispatch('success', "Book returned from $student->fullname");

    }
    public function render()
    {
        return view('livewire.library.book-lent-history', [
            'lents' =>  (new DataTable(new LentBook()))
                ->query(function ($query) {
                    $query->where('school_id', school()->id);
                    $query->where('book_id', $this->book->id);
                    $query->whereNull('returned_on');
                })
                ->searchable($this->search)
                ->paginate($this->paginate),
        ]);
    }
}
