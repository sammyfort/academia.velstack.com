<?php

namespace App\Livewire\OpenPortal\Admin;

use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class LogActivity extends Component
{
    use WithPagination, WithoutUrlPagination;
    public function render()
    {
        return view('livewire.open-portal.admin.log-activity', [
            'logs' => Activity::latest()->paginate()
        ]);
    }
}
