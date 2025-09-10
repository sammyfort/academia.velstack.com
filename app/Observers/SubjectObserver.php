<?php

namespace App\Observers;

use App\Models\Student;
use App\Models\Subject;
use App\Traits\ActivityLogger;
use Illuminate\Support\Facades\Cache;

class SubjectObserver
{
    use ActivityLogger;
    /**
     * Handle the Subject "created" event.
     */
    public function created(Subject $subject): void
    {
        $this->refreshCache($subject);
        $this->logCreated($subject, $subject->name, 'subjects.index');
    }

    /**
     * Handle the Subject "updated" event.
     */
    public function updated(Subject $subject): void
    {
        $this->refreshCache($subject);
        $this->logUpdated($subject, $subject->name, 'subjects.index');
    }

    /**
     * Handle the Subject "deleted" event.
     */
    public function deleted(Subject $subject): void
    {
        $this->refreshCache($subject);
        $this->logDeleted($subject, $subject->name);
    }

    /**
     * Handle the Subject "restored" event.
     */
    public function restored(Subject $subject): void
    {
        $this->refreshCache($subject);
        $this->logRestored($subject, $subject->name, 'subjects.index');
    }

    /**
     * Handle the Subject "force deleted" event.
     */
    public function forceDeleted(Subject $subject): void
    {
        $this->refreshCache($subject);
        $this->logDeleted($subject, $subject->name);
    }
    private function refreshCache(Subject $subject): void
    {
        Cache::forget("subjects:$subject->school_id");
    }
}
