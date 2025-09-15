<?php

namespace App\Livewire\Calender;

use App\Enum\TermStatus;
use App\Models\Term;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CalenderEdit extends Component
{
    public Term $term;

    public array $calender = [
        'name' => '',
        'start_date' => '',
        'end_date' => '',
        'status' => '',
        'days' => '',
        'next_term_begins' => '',
    ];

    public function mount($uuid):void
    {
        $this->term = school()->terms()->where('uuid', $uuid)->firstOrFail();
        $this->calender = $this->term->toArray();
        $this->calender['start_date'] = $this->term->start_date->format('Y-m-d');
        $this->calender['end_date'] = $this->term->end_date->format('Y-m-d');
    }

    protected function rules(): array
    {
        return [
            'calender.name' => ['required',
                Rule::unique('terms', 'name')->where('school_id', school()->id)->ignore($this->term)
            ],
            'calender.start_date' => ['required'],
            'calender.end_date' => ['required', 'date', 'after:calender.start_date'],
            'calender.status' => ['required', Rule::in(TermStatus::cases())],
            'calender.days' => ['required', 'numeric'],
            'calender.next_term_begins' => ['required', 'date'],
        ];
    }

    protected function getValidationAttributes(): array
    {
        return getValidationAttributesFor($this->rules());
    }

    public function update(): void
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $this->term->update($this->calender);
            if ($this->term->status == TermStatus::ACTIVE->value) {
                $exists = school()->terms()->where('status', TermStatus::ACTIVE->value)->get()->except($this->term->id);
                foreach ($exists as $term) {
                    $term->update(['status' => TermStatus::ENDED->value]);
                }
            }
            DB::commit();
            $this->dispatch('success', 'Calender updated successfully');
        }catch (\Exception $exception){
            DB::rollBack();
            $this->dispatch('error', "{$exception->getMessage()}");
        }

    }

    public function render()
    {
        return view('livewire.calender.calender-edit');
    }
}
