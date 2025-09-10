<?php

namespace App\Observers;

use App\Enum\ClassRole;
use App\Models\StaffClassroomSubjectPermission;
use App\Traits\ActivityLogger;

class StaffClassObserver
{
    use ActivityLogger;
    /**
     * Handle the StaffClassroomSubjectPermission "created" event.
     */
    public function created(StaffClassroomSubjectPermission $staffClass): void
    {
        $user = auth()->user();
        $subject = isset($staffClass->subject_id) ? $staffClass->subject->name : '';
        $this->logCreated($staffClass,
            $staffClass->classroom->name,
            'classes.assign.staff',
            'View Assignment',
            "$user->fullname assigned {$staffClass->staff->fullname} to class {$staffClass->classroom->name} as $staffClass->permission $subject");
    }

    /**
     * Handle the StaffClassroomSubjectPermission "updated" event.
     */
    public function updated(StaffClassroomSubjectPermission $staffClass): void
    {
        $user = auth()->user();
        $role = $staffClass->permission = ClassRole::CLASS_TEACHER->value ? 'class teacher' : 'subject teacher';
        $subject = isset($staffClass->subject_id) ? $staffClass->subject->name : '';
        $this->logUpdated($staffClass,
            $staffClass->classroom->name,
            'classes.assign.staff',
            'View Assignment',
            "$user->fullname updated {$staffClass->staff->fullname}'s role to class {$staffClass->classroom->name} as $role $subject");
    }

    /**
     * Handle the StaffClassroomSubjectPermission "deleted" event.
     */
    public function deleted(StaffClassroomSubjectPermission $staffClass): void
    {
        info('deleted');
        $user = auth()->user();
        $subject = isset($staffClass->subject_id) ? $staffClass->subject->name : '';
        $this->logDeleted($staffClass,
            $staffClass->classroom->name,
            "$user->fullname removed {$staffClass->staff->fullname} from class {$staffClass->classroom->name} as  $staffClass->permission  $subject");
    }

    /**
     * Handle the StaffClassroomSubjectPermission "restored" event.
     */
    public function restored(StaffClassroomSubjectPermission $staffClass): void
    {
        $user = auth()->user();
        $role = $staffClass->permission = ClassRole::CLASS_TEACHER->value ? 'class teacher' : 'subject teacher';
        $subject = isset($staffClass->subject_id) ? $staffClass->subject->name : '';
        $this->logRestored($staffClass,
            $staffClass->classroom->name,
            'classes.assign.staff',
            'View Assignment',
            "$user->fullname restored {$staffClass->staff->fullname} to class {$staffClass->classroom->name} as $role $subject");
    }

    /**
     * Handle the StaffClassroomSubjectPermission "force deleted" event.
     */
    public function forceDeleted(StaffClassroomSubjectPermission $staffClass): void
    {
        $user = auth()->user();
        $role = $staffClass->permission = ClassRole::CLASS_TEACHER->value ? 'class teacher' : 'subject teacher';
        $subject = isset($staffClass->subject_id) ? $staffClass->subject->name : '';
        $this->logDeleted($staffClass,
            $staffClass->classroom->name,

            "$user->fullname removed {$staffClass->staff->fullname} from class {$staffClass->classroom->name} as $role $subject");
    }


}
