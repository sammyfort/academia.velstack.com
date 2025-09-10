<?php

namespace App\Observers;

use App\Models\Staff;
use App\Traits\ActivityLogger;
use Illuminate\Support\Arr;

class StaffObserver
{
    use ActivityLogger;

    public function created(Staff $staff): void
    {
        $this->logCreated($staff, $staff->fullname, 'staff.show');
    }


    public function updated(Staff $staff): void
    {
        $this->logUpdated($staff, $staff->fullname, 'staff.show');
    }

    public function deleted(Staff $staff): void
    {
       $this->logDeleted($staff, $staff->fullname);
    }


    public function restored(Staff $staff): void
    {
       $this->logRestored($staff, $staff->fullname, 'staff.show');
    }


    public function forceDeleted(Staff $staff): void
    {
        $this->logDeleted($staff, $staff->fullname);
    }
}
