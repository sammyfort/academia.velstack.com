<?php

namespace App\Livewire\Timetable;

use App\Enum\TimetableDays;
use App\Models\Term;
use Livewire\Component;

class TimetableShow extends Component
{
    public Term $term;

    public function mount($uuid): void
    {
        $this->term = school()->terms()->where('uuid', $uuid)->firstOrFail();
    }
    public function render()
    {
        return view('livewire.timetable.timetable-show', [
            'timeSlots' => school()->timetables()
                ->where('term_id', $this->term->id)
                ->select('start_time', 'end_time')
                ->distinct()
                ->orderBy('start_time')
                ->get(),

            'days' => TimetableDays::cases(),
            'timetables' => school()->timetables()
                ->where('term_id', $this->term->id)
                ->with(['subject', 'staff'])->get(),
        ]);
    }
}
