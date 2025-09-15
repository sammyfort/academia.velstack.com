<?php

namespace App\Livewire\Library;

use App\Models\Book;
use Livewire\Component;

class BookEdit extends Component
{
    public array $book = [
        'title' => '',
        'author' => '',
        'isbn' => '',
        'copies' => ''
    ];

    public Book $bookModel;

    public function mount($uuid): void
    {
        $this->bookModel = school()->books()->where('uuid', $uuid)->firstOrFail();
        $this->book = $this->bookModel->toArray();
    }

    public function update(): void
    {
        $this->validate([
            'book.title' => ['required'],
            'book.author' => ['required'],
            'book.isbn' => ['required'],
            'book.copies' => ['required'],

        ]);
        $this->bookModel->update($this->book);
        $this->dispatch('success', 'Book updated.');

    }
    public function render()
    {
        return view('livewire.library.book-edit');
    }
}
