<?php

namespace App\Livewire\Event;

use App\Models\Event;
use App\Services\DataTable;
use Livewire\Attributes\On;
use Livewire\Component;

class EventIndex extends Component
{
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
    public function render()
    {
        return view('livewire.event.event-index', [
            'events' => (new DataTable(new Event()))
                ->query(function ($query) {
                    $query->where('school_id', school()->id);
                })
                ->searchable($this->search)
                ->paginate($this->paginate)
        ]);
    }
}
