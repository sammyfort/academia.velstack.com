<?php

namespace App\Observers;

use App\Models\Classroom;
use App\Traits\ActivityLogger;
use Illuminate\Support\Facades\Cache;

class ClassroomObserver
{
    use ActivityLogger;
    /**
     * Handle the Classroom "created" event.
     */
    public function created(Classroom $classroom): void
    {
        $this->refreshCache($classroom);
        $this->logCreated($classroom, $classroom->name, 'classes.show');
    }

    /**
     * Handle the Classroom "updated" event.
     */
    public function updated(Classroom $classroom): void
    {
        $this->refreshCache($classroom);
        $this->logUpdated($classroom, $classroom->name, 'classes.show');
    }

    /**
     * Handle the Classroom "deleted" event.
     */
    public function deleted(Classroom $classroom): void
    {
        $this->refreshCache($classroom);
        $this->logDeleted($classroom, $classroom->name);
    }

    /**
     * Handle the Classroom "restored" event.
     */
    public function restored(Classroom $classroom): void
    {
        $this->refreshCache($classroom);
        $this->logRestored($classroom, $classroom->name);
    }

    /**
     * Handle the Classroom "force deleted" event.
     */
    public function forceDeleted(Classroom $classroom): void
    {
        $this->refreshCache($classroom);
        $this->logDeleted($classroom, $classroom->name);
    }

    private function refreshCache(Classroom $classroom): void
    {
        Cache::forget("classes:$classroom->school_id");
        $versionKey = "classIndex:$classroom->school_id";
        Cache::increment($versionKey);
    }

}
