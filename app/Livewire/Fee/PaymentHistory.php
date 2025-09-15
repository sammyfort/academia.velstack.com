<?php

namespace App\Livewire\Fee;

use App\Models\Fee;
use App\Models\Payment;
use App\Services\DataTable;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class PaymentHistory extends Component
{
    use WithPagination, WithoutUrlPagination;
    public string $search = "";
    public int $paginate = 15;
    public string $direction = 'desc';

    public function resetFilter(): void
    {
        $this->search = "";
        $this->paginate = 10;
        $this->direction = 'desc';
    }



    #[On('recordDeleted')]
    public function render()
    {
        return view('livewire.fee.payment-history', [
            'payments' => (new DataTable(new Payment()))
                ->searchable($this->search)
                ->query(function ($query) {
                    $query->where('school_id', school()->id);
                })
                ->with(['student'])
                ->withCount('bills')
                ->latest()
                ->paginate($this->paginate)
        ]);
    }
}
