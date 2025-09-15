<?php

namespace App\Livewire\Timetable;

use App\Models\Term;
use App\Models\Timetable;
use App\Services\DataTable;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class TimetableIndex extends Component
{
    use WithPagination, WithoutUrlPagination;
    public string $search = "";
    public int $paginate = 10;
    public string $direction = 'desc';

    public ?int $term_id = null;
    public Term $term;

    public function mount(): void
    {
        $this->term = currentTerm();
        $this->term_id = $this->term->id;
    }

    public function updatedTermId($value): void
    {
        if ($value) {
            $this->term = school()->terms()->findOrFail($value);
        }

    }

    public function resetFilter(): void
    {
        $this->search = "";
        $this->paginate = 15;
        $this->direction = 'desc';
    }

    #[On('recordDeleted')]
    public function render()
    {
        return view('livewire.timetable.timetable-index', [
            'timetables' => (new DataTable( new Timetable() ) )
                ->query(function ($query) {
                    $query->where('school_id', school()->id);
                    $query->where('term_id', $this->term->id);
                })
                ->searchable($this->search)
                ->paginate($this->paginate),
            'terms' => school()->terms()->get()
        ]);

    }
}
