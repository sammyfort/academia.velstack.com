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
}
