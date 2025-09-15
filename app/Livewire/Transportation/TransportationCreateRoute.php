<?php

namespace App\Livewire\Transportation;

use Livewire\Component;

class TransportationCreateRoute extends Component
{
    public array $transportation = [
        'route' => '',
        'distance' => '',
        'fee_id' => '',
        'description' => '',
    ];

    public function create(): void
    {
        $this->validate([
           'transportation.route' => ['required', 'string', 'max:255'],
           'transportation.distance' => ['required', 'string'],
           'transportation.fee_id' => ['required', 'exists:fees,id'],
           'transportation.description' => ['required', 'string'],
        ]);

        school()->transportations()->create($this->transportation);
        $this->reset();
        $this->dispatch('success', 'Transportation has been created.');

    }
    public function render()
    {
        return view('livewire.transportation.transportation-create-route');
    }
}
