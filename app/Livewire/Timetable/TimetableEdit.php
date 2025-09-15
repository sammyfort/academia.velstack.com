<?php

namespace App\Livewire\Timetable;

use App\Enum\TimetableDays;
use App\Models\Timetable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TimetableEdit extends Component
{
    public array $timetable = [
        'term_id' => '',
        'class_id' => '',
        'subject_id' => '',
        'staff_id' => '',
        'day' => '',
        'start_time' => '',
        'end_time' => '',
    ];

    public Timetable $timeTable;

    public function mount($uuid): void
    {
        $this->timeTable = school()->timetables()->where('uuid', $uuid)->firstOrFail();
        $this->timeTable['start_time'] = $this->timeTable->start_time->format('H:i');
        $this->timetable = $this->timeTable->toArray();
    }

    protected function rules(): array
    {
        return [
            'timetable.term_id' => ['required'],
            'timetable.class_id' => ['required',],
            'timetable.subject_id' => ['required'],
            'timetable.staff_id' => ['required'],
            'timetable.day' => ['required', Rule::in(TimetableDays::cases())],
            'timetable.start_time' => [ 'required','date_format:H:i'],
            'timetable.end_time' => ['required','date_format:H:i','after:timetable.start_time'],
        ];
    }

    public function update(): void
    {

       // dd($this->timetable);
        $this->validate();
        $conflict = school()->timetables()
            ->where('id', '!=', $this->timeTable->id)
            ->where('staff_id', $this->timetable['staff_id'])
            ->where('term_id', $this->timetable['term_id'])
            ->where('day', $this->timetable['day'])
            ->where(function ($query) {
                $query->whereBetween('start_time', [$this->timetable['start_time'], $this->timetable['end_time']])
                    ->orWhereBetween('end_time', [$this->timetable['start_time'], $this->timetable['end_time']])
                    ->orWhere(function ($q) {
                        $q->where('start_time', '<=', $this->timetable['start_time'])
                            ->where('end_time', '>=', $this->timetable['end_time']);
                    });
            })->exists();

        if ($conflict) {
            $this->addError('timetable.staff_id', 'The selected staff already has a lesson scheduled in this time range on ' . $this->timetable['day']);
            return;
        }
        $this->timeTable->update($this->timetable);
        $this->dispatch('success', "Timetable updated");

    }
    public function render()
    {
        return view('livewire.timetable.timetable-edit', [
            'classes' => school()->classes()->get(),
            'staff' => school()->staff()->get(),
            'subjects' => school()->subjects()->get(),
            'terms' => school()->terms()->get()
        ]);
    }
}
