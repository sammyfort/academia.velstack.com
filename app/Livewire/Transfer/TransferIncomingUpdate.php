<?php

namespace App\Livewire\Transfer;

use App\Enum\TransferStatus;
use App\Traits\ActivityLogger;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TransferIncomingUpdate extends Component
{
    use ActivityLogger;

    public array $transfer = [
        'status' => '',
        'class_id' => null,
        'reason' => '',
        'school_name' => '',
        'school_region' => '',
        'school_address' => '',
        'school_number' => '',
        'student_name' => '',
        'student_class' => '',
        'requested_date' => '',
    ];
    public \App\Models\Transfer $transferModel;

    public function mount($uuid): void
    {
        $this->transferModel = school()->incomingTransfers()->where('uuid', $uuid)->firstOrFail();
        $this->transfer = [
            'status' => $this->transferModel->status,
            'reason' => $this->transferModel->reason,
            'school_name' => $this->transferModel->fromSchool->name,
            'school_region' => $this->transferModel->fromSchool->region,
            'school_address' => $this->transferModel->fromSchool->district,
            'school_number' => $this->transferModel->fromSchool->phone,
            'student_name' => $this->transferModel->transferable->fullname,
            'student_class' => $this->transferModel->transferable->class->name,
            'requested_date' => $this->transferModel->initiated_at->format('Y-m-d'),
        ];
    }

    public function update(): void
    {
        $this->validate([
            'transfer.status' => [ 'required', Rule::in(TransferStatus::cases())],
            'transfer.class_id' => [
                Rule::requiredIf(fn()=> $this->transfer['status'] === TransferStatus::APPROVED->value),
                Rule::exists('classrooms', 'id')->where('school_id', school()->id)]
        ]);

        DB::beginTransaction();

        try {
            $transfer = $this->transferModel;

            switch ($this->transfer['status']) {
                case TransferStatus::APPROVED->value:
                    $transfer->update([
                        'status' => TransferStatus::APPROVED->value,
                        'approved_at' => now(),
                    ]);
                    $student = $transfer->transferable;
                    $class = school()->classes()->findOrFail($this->transfer['class_id']);
                    $student->update([
                        'school_id' => $transfer->to_school_id,
                        'previous_school_id' => $transfer->from_school_id,
                        'class_id' => $class->id,
                    ]);
                    foreach ($student->bills as $bill) {
                        $bill->delete();
                    }
                    $this->logUpdated($transfer,
                        $transfer->transferable->fullname,
                        'transfers.incoming',
                        'View',
                        "Transfer approved");
                    break;

                case TransferStatus::PENDING->value:

                    break;
                case TransferStatus::REJECTED->value:
                    $transfer->update([
                        'status' => TransferStatus::REJECTED->value,
                        'rejected_at' => now(),
                    ]);

                    $this->logUpdated(
                        $transfer,
                        $transfer->transferable->fullname,
                        'transfers.incoming',
                        'View',
                        "Transfer rejected"
                    );
                    break;
                default:
                    throw new Exception('Invalid transfer status.');

            }
            DB::commit();
            $this->dispatch('success', 'Transfer approved');
            $this->redirect( route('transfers.incoming'));
        } catch (Exception $e) {
            DB::rollBack();
           // throw $e;
            $this->dispatch('error', $e->getMessage());
        }

    }


    public function render()
    {
        return view('livewire.transfer.transfer-incoming-update', [
            'classes' => school()->classes()->get()
        ]);
    }
}
