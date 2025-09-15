<?php

namespace App\Livewire\OpenPortal\Admin;

use App\Models\School;
use App\Models\Transaction;
use App\Services\DataTable;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class SubscriptionIndex extends Component
{
    use WithPagination, WithoutUrlPagination;
    public string $search = '';


    public int $paginate = 2;
    public ?int $school_id = null;



    public function resetFilter(): void
    {
        $this->search = "";


    }
    public function render()
    {
        return view('livewire.open-portal.admin.subscription-index', [
          'subscriptions' =>  (new DataTable(new Transaction()))
                ->searchable($this->search)
                ->query(function ($query) {

                    $query->where('school_id', 'like', '%' . $this->school_id . '%');
                })
                ->latest()
                ->paginate($this->paginate),
        ]);
    }
}
