<?php

namespace App\Observers;

use App\Models\ScoreType;
use App\Traits\ActivityLogger;

class ScoreTypeObserver
{
    use ActivityLogger;
    /**
     * Handle the ScoreType "created" event.
     */
    public function created(ScoreType $scoreType): void
    {
        $this->logCreated($scoreType, " for {$scoreType->classroom->name} | $scoreType->name");
    }

    /**
     * Handle the ScoreType "updated" event.
     */
    public function updated(ScoreType $scoreType): void
    {
        $this->logUpdated($scoreType, " for {$scoreType->classroom->name} | $scoreType->name");
    }

    /**
     * Handle the ScoreType "deleted" event.
     */
    public function deleted(ScoreType $scoreType): void
    {
        $this->logDeleted($scoreType, " for {$scoreType->classroom->name} | $scoreType->name");
    }

    /**
     * Handle the ScoreType "restored" event.
     */
    public function restored(ScoreType $scoreType): void
    {
        $this->logRestored($scoreType, " for {$scoreType->classroom->name} | $scoreType->name");
    }

    /**
     * Handle the ScoreType "force deleted" event.
     */
    public function forceDeleted(ScoreType $scoreType): void
    {
        $this->logDeleted($scoreType, " for {$scoreType->classroom->name} | $scoreType->name");
    }
}
