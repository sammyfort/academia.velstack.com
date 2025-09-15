<?php

namespace App\Livewire\Subject;

use App\Models\Subject;
use App\Services\DataTable;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class SubjectIndex extends Component
{

    use WithPagination, WithoutUrlPagination;
    public string $search = "";
    public int $paginate = 10;
    public string $direction = 'desc';

    public function resetFilter(): void
    {
        $this->search = "";
        $this->paginate = 15;
        $this->direction = 'desc';
    }



    #[On('recordDeleted')]
    public function render(): View
    {
        return view('livewire.subject.subject-index', [
            'subjects' => (new DataTable(new Subject()))
            ->query(function ($query) {
                $query->where('school_id', school()->id);
            })
                ->withCount(['classes', 'students'])
            ->searchable($this->search)
            ->paginate($this->paginate)
        ]);
    }
}
