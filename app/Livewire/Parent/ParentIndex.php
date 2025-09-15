<?php

namespace App\Livewire\Parent;

use App\Models\_Parent;
use App\Models\Student;
use App\Services\DataTable;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ParentIndex extends Component
{
    use WithPagination, WithoutUrlPagination;

    public string $search = '';

    public function resetFilter(): void
    {
        $this->search = '';
    }


    #[On('recordDeleted')]
    public function render()
    {
        return view('livewire.parent.parent-index', [
            'parents' => (new DataTable(new _Parent()))
                ->searchable($this->search)
                ->query(function ($query) {
                    $query->where('school_id', school()->id);
                })
                ->latest()
                ->paginate(15),
        ]);
    }
}
