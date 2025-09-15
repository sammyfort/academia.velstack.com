<?php

namespace App\Livewire\Staff;

use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

#[Layout('layouts.app')]
class StaffShow extends Component
{
    use WithPagination, WithoutUrlPagination;
    public Staff $staff;

    public function mount($uuid): void
    {
        $this->staff = school()->staff()->where('uuid', $uuid)->firstOrFail();
    }

    public function removeSubject($staffId, $classroomId, $subjectId): void
    {
        detachSubject($staffId, $classroomId, $subjectId);
    }

    public function removeClassroom($staffId, $classroomId): void
    {
        detachClassroom($staffId, $classroomId);
    }
    public function render()
    {
        return view('livewire.staff.staff-show', [
            'logs' => Activity::whereHasMorph('causer', [Staff::class],
                function ($query) {
                    $query->where('school_id', school()->id);
                })
                ->where('causer_id', $this->staff->id)
                ->latest()
                ->paginate(10)
        ]);
    }
}
