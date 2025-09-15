<?php

namespace App\Livewire\OpenPortal\Admin;

use App\Models\Application;
use App\Models\Subject;
use App\Services\DataTable;
use Livewire\Component;

class ApplicationIndex extends Component
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

    public function render()
    {
        return view('livewire.open-portal.admin.application-index', [
            'applications' => (new DataTable(new Application()))
                ->query(function ($query) {

                })
                ->searchable($this->search)
                ->paginate($this->paginate)
        ]);
    }
}
