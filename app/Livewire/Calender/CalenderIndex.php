<?php

namespace App\Livewire\Calender;

use App\Models\Term;
use App\Services\DataTable;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class CalenderIndex extends Component
{
    use WithPagination, WithoutUrlPagination;
    public string $search = '';
    public int $paginate = 15;
    public string $date = '';
    public string $status = '';

    public function resetFilter(): void
    {
        $this->reset(['search', 'date', 'status', 'paginate']);
        $this->resetPage();
    }



    #[On('recordDeleted')]
    public function render(): View
    {

        return view('livewire.calender.calender-index', [
            'calenders' => (new DataTable(new Term()))
                ->query(function ($query) {
                    $query->where('school_id',  staff()->school->id);
                })->latest()
                ->searchable($this->search)
                ->paginate($this->paginate)
        ]);
    }
}
