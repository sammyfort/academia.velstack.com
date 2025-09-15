<?php

namespace App\Livewire\Transfer;

use App\Enum\TransferStatus;
use App\Models\School;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TransferInitiate extends Component
{

    public ?int $student_id = null;
    public string $reason = '';
    public ?int $to_school_id = null;


    public function transfer(): void
    {
        $this->validate([
           'student_id' => ['required', 'integer', Rule::exists('students', 'id')->where('school_id', school()->id)],
            'reason' => ['required', 'string'],
            'to_school_id' => ['required', 'integer', Rule::exists('schools', 'id')],
        ]);

        $student = school()->students()->findOrFail($this->student_id);
        $existingTransfer = $student->transfers()->where('status', TransferStatus::PENDING->value)->first();

        if ($existingTransfer) {
           $this->addError('student_id','This student already has an ongoing transfer request');
           return;
        }

        $student->transfers()->create([
            'from_school_id' => $student->school_id,
            'to_school_id' => $this->to_school_id,
            'reason' => $this->reason,
            'initiated_at' => now(),
            'status' => TransferStatus::PENDING->value,
        ]);
        $this->dispatch('success', 'Transfer requested');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.transfer.transfer-initiate', [
            'students' => school()->students()->whereDoesntHave('transfers', function ($query) {
                $query->where('status', TransferStatus::PENDING->value);
            })->get(),

            'schools' => School::query()->whereHas('preference', function ($query) {
                $query->where('open_for_transfer', true);
            })->get()->except(school()->id)
        ]);
    }
}
