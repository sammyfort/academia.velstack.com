<?php

namespace App\Livewire\Event;

use App\Enum\EventNotificationIntervals;
use App\Enum\NotifyClass;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EventCreate extends Component
{
    public array $event = [
        'name' => '',
        'start_date' => '',
        'end_date' => '',
        'description' => '',
        'send_notification_to' => '',
        'interval' => '',
        'message' => ''
    ];

    public function create(): void
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

        school()->events()->create($this->event);
        $this->dispatch('success', 'Event Created');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.event.event-create');
    }
}
