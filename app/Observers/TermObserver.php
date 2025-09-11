<?php

namespace App\Observers;

use App\Models\Term;
use App\Traits\ActivityLogger;
use Illuminate\Support\Facades\Cache;

class TermObserver
{
    use ActivityLogger;
    /**
     * Handle the Term "created" event.
     */
    public function created(Term $term): void
    {
//        $this->refreshCache($term);
//        $this->logCreated($term, $term->name, 'calenders.index');
    }

    /**
     * Handle the Term "updated" event.
     */
    public function updated(Term $term): void
    {
//        $this->refreshCache($term);
//        $this->logUpdated($term, $term->name, 'calenders.index');
    }

    /**
     * Handle the Term "deleted" event.
     */
    public function deleted(Term $term): void
    {
//        $this->refreshCache($term);
//        $this->logDeleted($term, $term->name);
    }

    /**
     * Handle the Term "restored" event.
     */
    public function restored(Term $term): void
    {
//        $this->refreshCache($term);
//        $this->logRestored($term, $term->name, 'calenders.index');
    }

    /**
     * Handle the Term "force deleted" event.
     */
    public function forceDeleted(Term $term): void
    {
//        $this->refreshCache($term);
//        $this->logDeleted($term, $term->name);
    }

    private function refreshCache(Term $term): void
    {
        Cache::forget("terms:$term->school_id");
        Cache::forget("currentTerm:$term->school_id");

    }
}
