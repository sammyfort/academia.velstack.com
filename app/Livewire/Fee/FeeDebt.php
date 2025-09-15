<?php

namespace App\Livewire\Fee;

use App\Models\Fee;
use App\Services\DataTable;
use App\Traits\CacheStore;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;


class FeeDebt extends Component
{
     use WithPagination, WithoutUrlPagination, CacheStore;
    public string $query = '';
    public ?int $class_id = null;
    public ?int $student_id = null;
    public ?int $fee_id = null;


    public function resetFilter(): void
    {
        $this->query = '';
        $this->class_id = null;
        $this->student_id = null;
        $this->fee_id = null;
    }


    public function render()
    {
        return view('livewire.fee.fee-debt', [
            'students' => school()
                ->studentsOwing($this->class_id, $this->student_id, $this->fee_id, $this->query)
                ->paginate(),
            'classes' => $this->getClasses(),
            'pupils' => $this->getStudents(['class']),
            'fees' => $this->getFees(),
        ]);
    }
}
