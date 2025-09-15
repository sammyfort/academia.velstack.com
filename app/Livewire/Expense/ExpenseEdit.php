<?php

namespace App\Livewire\Expense;

use App\Enum\ExpenseCategory;
use App\Enum\ExpenseStatus;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ExpenseEdit extends Component
{
    public array $expense = [
        'description' => '',
        'category' => '',
        'amount' => '',
        'expense_date' => '',
        'status' => '',
    ];

    public Expense $expenseModel;

    public function mount($uuid): void
    {
        $this->expenseModel = school()->expenses()->where('uuid', $uuid)->firstOrFail();
        $this->expense = $this->expenseModel->toArray();
    }

    public function update(): void
    {

        $this->validate([
            'expense.description' => ['required'],
            'expense.category' => ['required', Rule::in(ExpenseCategory::cases())],
            'expense.amount' => ['required', 'numeric'],
            'expense.expense_date' => ['required', 'date'],
            'expense.status' => ['required', Rule::in(ExpenseStatus::cases())],
        ]);

        DB::beginTransaction();
        try {
            $this->expenseModel->update($this->expense);
            DB::commit();
            $this->dispatch('success', 'Expense updated');

        } catch (\Exception $exception) {
            DB::rollBack();
            $this->dispatch('error', $exception->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.expense.expense-edit');
    }
}
