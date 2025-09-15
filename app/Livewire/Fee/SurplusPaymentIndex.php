<?php

namespace App\Livewire\Fee;

use Livewire\Component;

class SurplusPaymentIndex extends Component
{
    public string $search = '';
    public ?int $class_id = null;
    public ?int $student_id = null;

    public function resetFilter(): void
    {
        $this->search = '';
        $this->class_id = null;
        $this->student_id = null;

    }
    public function render()
    {
        return view('livewire.fee.surplus-payment-index', [
            'students' => school()->students()->withOverflows()
                ->search($this->class_id, $this->student_id,$this->search)->paginate(10),
            'classes' => school()->classes()->get(),
            'pupils' => school()->students()->get()
        ]);
    }
}
