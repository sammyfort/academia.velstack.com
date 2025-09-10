<?php

namespace App\Observers;

use App\Models\Book;
use App\Traits\ActivityLogger;

class BookObserver
{
    use ActivityLogger;
    /**
     * Handle the Book "created" event.
     */
    public function created(Book $book): void
    {
        $this->logCreated($book, $book->title, 'library.book.create');
    }

    /**
     * Handle the Book "updated" event.
     */
    public function updated(Book $book): void
    {
        $this->logUpdated($book, $book->title, 'library.book.create');
    }

    /**
     * Handle the Book "deleted" event.
     */
    public function deleted(Book $book): void
    {
        $this->logDeleted($book, $book->title);
    }

    /**
     * Handle the Book "restored" event.
     */
    public function restored(Book $book): void
    {
        $this->logRestored($book, $book->title, 'library.book.create');
    }

    /**
     * Handle the Book "force deleted" event.
     */
    public function forceDeleted(Book $book): void
    {
        $this->logDeleted($book, $book->title);
    }
}
