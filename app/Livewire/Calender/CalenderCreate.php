<?php

namespace App\Livewire\Calender;

use App\Enum\TermStatus;
use App\Traits\Toaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class CalenderCreate extends Component
{

    public array  $calender = [
        'name' => '',
        'start_date' => '',
        'end_date' => '',
        'status' => TermStatus::ACTIVE->value,
        'days' => '',
        'next_term_begins' => ''
    ];

    protected function rules(): array
    {
        return [
            'calender.name' => ['required',
                Rule::unique('terms', 'name')->where('school_id', school()->id)
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
    public function create(): void
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $term =  school()->terms()->create($this->calender);
            if ($term->status == TermStatus::ACTIVE->value) {
                $exists = school()->terms()->where('status', TermStatus::ACTIVE->value)->get()->except($term->id);
                foreach ($exists as $term) {
                    $term->update(['status' => TermStatus::ENDED->value]);
                }
            }
            DB::commit();

            $this->dispatch('success', 'Calender added successfully');
            $this->reset();
        }catch (\Exception $exception){
            DB::rollBack();
            $this->dispatch('error', "{$exception->getMessage()}");
        }



    }
    public function render(): View
    {
        return view('livewire.calender.calender-create');
    }
}
