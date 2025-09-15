<?php

namespace App\Livewire\Event;

use App\Enum\NotifyClass;
use Illuminate\Validation\Rule;
use Livewire\Component;
use App\Models\Event;

class EventEdit extends Component
{

    public Event $eventer;

    public array $event = [
        'name' => '',
        'start_date' => '',
        'end_date' => '',
        'description' => '',
        'send_notification_to' => '',
        'interval' => '',
        'message' => ''
    ];

    public function mount($uuid): void
    {
        $this->eventer = school()->events()->where('uuid', $uuid)->firstOrFail();
        $this->event = $this->eventer->toArray();
    }

    public function update(): void
    {
        $this->validate([
            'event.name' => ['required', 'string'],
            'event.start_date' => ['required', 'date'],
            'event.end_date' => ['required', 'date'],
            'event.description' => ['required'],
            'event.send_notification_to' => ['required', Rule::in(NotifyClass::cases())],
            'event.message' => [Rule::requiredIf(fn() => $this->event['send_notification_to'] != NotifyClass::NONE->value), 'string'],
            'event.interval' => [Rule::requiredIf(fn() => $this->event['send_notification_to'] != NotifyClass::NONE->value), 'integer'],
        ]);

        $this->eventer->update($this->event);
        $this->dispatch('success', 'Event Updated');

    }
    public function render()
    {
        return view('livewire.event.event-edit');
    }
}
