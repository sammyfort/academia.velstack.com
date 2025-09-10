<?php

namespace App\Observers;

use App\Models\Event;
use App\Traits\ActivityLogger;

class EventObserver
{
    use ActivityLogger;
    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        $this->logCreated($event, $event->name, 'events.index');
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        $this->logUpdated($event, $event->name, 'events.index');
    }

    /**
     * Handle the Event "deleted" event.
     */
    public function deleted(Event $event): void
    {
        $this->logDeleted($event, $event->name);
    }

    /**
     * Handle the Event "restored" event.
     */
    public function restored(Event $event): void
    {
        $this->logRestored($event, $event->name, 'events.index');
    }

    /**
     * Handle the Event "force deleted" event.
     */
    public function forceDeleted(Event $event): void
    {
        $this->logDeleted($event, $event->name);
    }
}
