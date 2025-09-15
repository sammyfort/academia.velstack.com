<?php

namespace App\Livewire\System;

use App\Models\Staff;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class Logs extends Component
{
    use WithPagination, WithoutUrlPagination;
    public function render()
    {
        $school = school();
        return view('livewire.system.logs', [
            'logs' =>  Activity::whereHasMorph('causer', [Staff::class], function ($query) use ($school) {
                $query->where('school_id', $school->id);
            })
                ->with(['subject', 'causer'])
                ->latest()
                ->paginate()
        ]);
    }
}
