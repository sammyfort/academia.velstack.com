<?php

namespace App\Livewire\Transfer;

use App\Enum\TransferStatus;
use App\Models\_Parent;
use App\Models\Student;
use App\Models\Transfer;
use App\Services\DataTable;
use Livewire\Attributes\On;
use Livewire\Component;

class TransferOutgoing extends Component
{
    public string $search = '';

    public function resetFilter(): void
    {
        $this->search = '';

    }


    public function cancel($transfer_id): void
    {
      $transfer = school()->outgoingTransfers()->find($transfer_id);
      if ($transfer) {
          $transfer->delete();
          $this->dispatch('success', 'Transfer has been canceled');
          return;
      }
      $this->dispatch('error', 'Transfer not found');

    }

    #[On('recordDeleted')]
    public function render()
    {
        return view('livewire.transfer.transfer-outgoing', [
            'outgoingTransfers' => (new DataTable(new Transfer()))
                ->searchable($this->search)
                ->query(function ($query) {
                    $query->where('from_school_id', school()->id);
                    $query->where('transferable_type', Student::class);
                    $query->where('status', '!=', TransferStatus::APPROVED->value);
                })
                ->latest()
                ->paginate(15),
        ]);
    }
}
