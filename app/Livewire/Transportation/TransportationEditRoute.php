<?php

namespace App\Livewire\Transportation;

use App\Models\Transportation;
use Livewire\Component;

class TransportationEditRoute extends Component
{
    public Transportation $transport;
    public array $transportation = [
        'route' => '',
        'fee_id' => '',
        'distance' => '',
        'description' => ''
    ];
    public function mount($uuid): void
    {
        $this->transport = school()->transportations()->where('uuid', $uuid)->firstOrFail();
        $this->transportation = $this->transport->toArray();
    }

    public function update(): void
    {
        $this->validate([
            'transportation.route' => ['required', 'string', 'max:255'],
            'transportation.distance' => ['required', 'string'],
            'transportation.fee_id' => ['required', 'exists:fees,id'],
            'transportation.description' => ['required', 'string'],
        ]);

        $this->transport->update($this->transportation);
        $this->dispatch('success', 'Transportation has been updated.');
    }
    public function render()
    {
        return view('livewire.transportation.transportation-edit-route');
    }
}
