<?php

namespace App\Observers;

use App\Models\Transportation;
use App\Traits\ActivityLogger;

class TransportationObserver
{
    use ActivityLogger;
    /**
     * Handle the Transportation "created" event.
     */
    public function created(Transportation $transportation): void
    {
        $this->logCreated($transportation, $transportation->route, 'transportations.index');
    }

    /**
     * Handle the Transportation "updated" event.
     */
    public function updated(Transportation $transportation): void
    {
        $this->logUpdated($transportation, $transportation->route, 'transportations.index');
    }

    /**
     * Handle the Transportation "deleted" event.
     */
    public function deleted(Transportation $transportation): void
    {
        $this->logDeleted($transportation, $transportation->route );
    }

    /**
     * Handle the Transportation "restored" event.
     */
    public function restored(Transportation $transportation): void
    {
        $this->logRestored($transportation, $transportation->route, 'transportations.index');
    }

    /**
     * Handle the Transportation "force deleted" event.
     */
    public function forceDeleted(Transportation $transportation): void
    {
        $this->logDeleted($transportation, $transportation->route );
    }
}
