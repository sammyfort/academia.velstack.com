<?php

namespace App\Livewire\OpenPortal\Admin;

use App\Enum\Region;
use App\Models\School;
use App\Models\Student;
use App\Services\DataTable;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class SchoolIndex extends Component
{
    use WithPagination, WithoutUrlPagination;
    public string $search = '';
    public $date = '';
    public string  $region = '';

    public int $paginate = 25;



    public function resetFilter(): void
    {
        $this->search = "";
        $this->date = "";
        $this->region = "";

    }

    public function render()
    {
        return view('livewire.open-portal.admin.school-index', [
            'schools' => (new DataTable(new School()))
                ->searchable($this->search)
                ->query(function ($query) {

                    $query->where('region', 'like', '%' . $this->region . '%');
                })
                ->latest()
                ->paginate($this->paginate),
            'regions' => Region::cases(),
        ]);
    }
}
