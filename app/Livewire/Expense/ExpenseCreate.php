<?php

namespace App\Livewire\Expense;

use App\Enum\ExpenseCategory;
use App\Enum\ExpenseStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ExpenseCreate extends Component
{
    public array $expense = [
        'description' => '',
        'category' => '',
        'amount' => '',
        'expense_date' => '',
        'status' => ExpenseStatus::PENDING->value,
    ];

    public function create(): void
    {

        $this->validate([
            'expense.description' => ['required'],
            'expense.category' => ['required', Rule::in(ExpenseCategory::cases())],
            'expense.amount' => ['required', 'numeric'],
            'expense.expense_date' => ['required', 'date'],
            'expense.status' => ['required', Rule::in(ExpenseStatus::cases())]
        ]);

        DB::beginTransaction();
        try {
            school()->expenses()->create($this->expense);
            DB::commit();
            $this->dispatch('success', 'Expense created');
            $this->reset();
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->dispatch('error', $exception->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.expense.expense-create');
    }
}
