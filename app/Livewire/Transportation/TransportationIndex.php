<?php

namespace App\Livewire\Transportation;

use App\Models\Term;
use App\Models\Transportation;
use App\Services\DataTable;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class TransportationIndex extends Component
{
    use WithPagination, WithoutUrlPagination;
    public string $search = '';
    public int $paginate = 15;

    public function resetFilter(): void
    {
        $this->reset(['search','paginate']);
    }

    public function render()
    {
        return view('livewire.transportation.transportation-index', [
          'routes' =>  (new DataTable(new Transportation()))
                ->query(function ($query) {
                    $query->where('school_id',  staff()->school->id);
                })
                ->searchable($this->search)
                ->paginate($this->paginate)
        ]);
    }
}
