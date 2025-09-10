<?php

namespace App\Observers;

use App\Models\School;

class SchoolObserver
{

    public function created(School $school): void
    {
        $school->subscription()->create(['expires_at' => now()->addDays(30)]);
        $school->communication()->create([]);
        $school->preference()->create([]);
    }


    public function updated(School $school): void
    {
        //
    }


    public function deleted(School $school): void
    {
        //
    }


    public function restored(School $school): void
    {
        //
    }


    public function forceDeleted(School $school): void
    {
        //
    }
}
