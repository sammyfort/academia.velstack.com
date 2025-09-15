<?php

namespace App\Livewire\Parent;

use App\Models\Student;
use App\Services\DataTable;
use Livewire\Component;

class ParentWelcome extends Component
{
    public string $search = '';

    public function resetFilter(): void
    {
        $this->search = "";

    }
    public function render()
    {
        return view('livewire.parent.parent-welcome', [
            'students' => (new DataTable(new Student()))
                ->query(function ($query) {
                    $query->whereHas('parents', function ($parentQuery) {
                        $parentQuery->where('__parents.id', parent()->id);
                    });
                })
                ->searchable($this->search)
                ->paginate(),
        ]);
    }

}
