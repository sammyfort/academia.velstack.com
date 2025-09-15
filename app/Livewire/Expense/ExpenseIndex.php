<?php

namespace App\Livewire\Expense;

use App\Models\Expense;
use App\Models\Term;
use App\Services\DataTable;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ExpenseIndex extends Component
{
    use WithPagination, WithoutUrlPagination;
    public string $search = '';
    public int $paginate = 15;
    public string $date = '';
    public string $category = '';

    public function resetFilter(): void
    {
        $this->reset(['search', 'date', 'category', 'paginate']);

    }



    #[On('recordDeleted')]
    public function render()
    {
        return view('livewire.expense.expense-index', [
            'expenses' => (new DataTable(new Expense()))
                ->query(function ($query) {
                    $query->where('school_id',  staff()->school->id);
                    $query->where('expense_date', 'LIKE', '%' . $this->date . '%');
                    $query->where('category', 'LIKE', '%' . $this->category . '%');
                })->latest()
                ->searchable($this->search)
                ->paginate($this->paginate)
        ]);
    }
}
