<?php

namespace App\Livewire\Library;

use App\Enum\UserType;
use App\Models\Book;
use App\Models\School;
use App\Services\DataTable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class BookIndex extends Component
{
    public string $search = "";
    public int $paginate = 10;
    public string $direction = 'desc';

    public array|Collection $lentBooks = [];

    public function resetFilter(): void
    {
        $this->search = "";
        $this->paginate = 15;
        $this->direction = 'desc';
    }

    #[On('recordDeleted')]
    public function render()
    {
        return view('livewire.library.book-index', [
            'books' => (new DataTable(new Book()))
                ->query(function ($query) {
                    $query->where('school_id', school()->id);
                })
                ->searchable($this->search)
                ->paginate($this->paginate),
        ]);
    }
}
