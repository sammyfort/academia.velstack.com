<?php

namespace App\Observers;

use App\Jobs\SMSSenderJob;
use App\Models\_Parent;
use App\Models\Student;
use App\Traits\ActivityLogger;
use Illuminate\Support\Facades\Cache;

class ParentObserver
{
    use ActivityLogger;
    /**
     * Handle the _Parent "created" event.
     */
    public function created(_Parent $_parent): void
    {
        $this->logCreated($_parent, $_parent->name, 'parents.index');

        dispatch(new SMSSenderJob($_parent->school,
            "Dear $_parent->name, Welcome to {$_parent->school->name}. You can always track records of
            your child by visiting this link ". config('app.url'). ". This is your default login credentials".
            " email: $_parent->email. password: $_parent->phone. It's advisable to reset your password",
            $_parent->phone));
    }

    /**
     * Handle the _Parent "updated" event.
     */
    public function updated(_Parent $_parent): void
    {
        $this->logUpdated($_parent, $_parent->name, 'parents.index');
    }

    /**
     * Handle the _Parent "deleted" event.
     */
    public function deleted(_Parent $_parent): void
    {
        $this->refreshCache($_parent);
        $this->logDeleted($_parent, $_parent->name);
    }

    /**
     * Handle the _Parent "restored" event.
     */
    public function restored(_Parent $_parent): void
    {
        $this->refreshCache($_parent);
        $this->logRestored($_parent, $_parent->name, 'parents.index');
    }

    /**
     * Handle the _Parent "force deleted" event.
     */
    public function forceDeleted(_Parent $_parent): void
    {
        $this->refreshCache($_parent);
        $this->logDeleted($_parent, $_parent->name);
    }

    private function refreshCache(_Parent $parent): void
    {
        Cache::forget("parents:$parent->school_id");

    }
}
