<?php

namespace App\Observers;

use App\Models\Fee;
use App\Traits\ActivityLogger;
use Illuminate\Support\Facades\Cache;

class FeeObserver
{
    use ActivityLogger;
    /**
     * Handle the Fee "created" event.
     */
    public function created(Fee $fee): void
    {
        $this->refreshCache($fee);
        $this->logCreated($fee, $fee->name, 'finance.fee.index');
    }

    /**
     * Handle the Fee "updated" event.
     */
    public function updated(Fee $fee): void
    {
        $this->refreshCache($fee);
        $this->logUpdated($fee, $fee->name, 'finance.fee.index');
    }

    /**
     * Handle the Fee "deleted" event.
     */
    public function deleted(Fee $fee): void
    {
        $this->refreshCache($fee);
        $this->logDeleted($fee, $fee->name );
    }

    /**
     * Handle the Fee "restored" event.
     */
    public function restored(Fee $fee): void
    {
        $this->refreshCache($fee);
        $this->logRestored($fee, $fee->name, 'finance.fee.index');
    }

    /**
     * Handle the Fee "force deleted" event.
     */
    public function forceDeleted(Fee $fee): void
    {
        $this->refreshCache($fee);
        $this->logDeleted($fee, $fee->name);
    }
    private function refreshCache(Fee $fee): void
    {
        Cache::forget("fees:$fee->school_id");

    }
}
