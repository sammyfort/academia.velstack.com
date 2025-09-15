<?php

namespace App\Livewire\Library;

use Livewire\Component;

class BookCreate extends Component
{
    public array $book = [
        'title' => '',
        'author' => '',
        'isbn' => '',
        'copies' => ''
    ];

    public function create(): void
    {
        $this->validate([
           'book.title' => ['required'],
           'book.author' => ['required'],
           'book.isbn' => ['required'],
           'book.copies' => ['required'],

        ]);
        school()->books()->create($this->book);
        $this->reset();
        $this->dispatch('success', 'Book created.');

    }
    public function render()
    {
        return view('livewire.library.book-create');
    }
}
