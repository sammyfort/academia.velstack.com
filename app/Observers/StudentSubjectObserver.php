<?php

namespace App\Observers;

use App\Enum\ClassRole;
use App\Models\StudentSubject;
use App\Traits\ActivityLogger;

class StudentSubjectObserver
{
    use ActivityLogger;
    /**
     * Handle the StudentSubject "created" event.
     */
    public function created(StudentSubject $studentSubject): void
    {
        $user = auth()->user();
        $this->logCreated($studentSubject,
            $studentSubject->classroom->name,
            'classes.assign.staff',
            'View Assignment',
            "$user->fullname assigned subject {$studentSubject->subject->name} to student {$studentSubject->student->fullname} in {$studentSubject->classroom->name}");
    }



    /**
     * Handle the StudentSubject "updated" event.
     */
    public function updated(StudentSubject $studentSubject): void
    {
        $user = auth()->user();

        $this->logUpdated($studentSubject,
            $studentSubject->classroom->name,
            'classes.assign.staff',
            'View Assignment',
            "$user->fullname updated subject {$studentSubject->subject->name} from student {$studentSubject->student->fullname} in {$studentSubject->classroom->name}");
    }

    /**
     * Handle the StudentSubject "deleted" event.
     */
    public function deleted(StudentSubject $studentSubject): void
    {
        $user = auth()->user();

        $this->logDeleted($studentSubject,
            $studentSubject->classroom->name,

            "$user->fullname removed subject {$studentSubject->subject->name} from student {$studentSubject->student->fullname} in {$studentSubject->classroom->name}");

    }

    /**
     * Handle the StudentSubject "restored" event.
     */
    public function restored(StudentSubject $studentSubject): void
    {
        $user = auth()->user();

        $this->logRestored($studentSubject,
            $studentSubject->classroom->name,
            'classes.assign.staff',
            'View Assignment',
            "$user->fullname restored subject {$studentSubject->subject->name} from student {$studentSubject->student->fullname} in {$studentSubject->classroom->name}");

    }

    /**
     * Handle the StudentSubject "force deleted" event.
     */
    public function forceDeleted(StudentSubject $studentSubject): void
    {
        $user = auth()->user();
        $this->logDeleted($studentSubject,
            $studentSubject->classroom->name,

            "$user->fullname removed subject {$studentSubject->subject->name} from student {$studentSubject->student->fullname} in {$studentSubject->classroom->name}");

    }
}
