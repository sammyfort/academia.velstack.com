<?php

namespace App\Livewire\Transfer;

use App\Enum\TransferStatus;
use App\Models\Student;
use App\Models\Transfer;
use App\Services\DataTable;
use App\Traits\ActivityLogger;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TransferIncoming extends Component
{
    use ActivityLogger;
    public string $search = '';

    public function resetFilter(): void
    {
        $this->search = '';

    }

    public function render()
    {
        return view('livewire.transfer.transfer-incoming', [
            'incomingTransfers' => (new DataTable(new Transfer()))
                ->searchable($this->search)
                ->query(function ($query) {
                    $query->where('to_school_id', school()->id);
                    $query->where('transferable_type', Student::class);
                    $query->where('status', '!=', TransferStatus::APPROVED->value);
                })
                ->latest()
                ->paginate(15),
        ]);
    }
}
