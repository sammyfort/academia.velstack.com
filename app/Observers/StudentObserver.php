<?php

namespace App\Observers;

use App\Models\Student;
use App\Traits\ActivityLogger;
use Illuminate\Support\Facades\Cache;

class StudentObserver
{
    use ActivityLogger;
    public function created(Student $student): void
    {
        $this->refreshCache($student);
        $this->logCreated($student, $student->fullname, 'students.show');
    }


    public function updated(Student $student): void
    {
        $this->refreshCache($student);
        $this->logUpdated($student, $student->fullname, 'students.show');
    }

    public function deleted(Student $student): void
    {
        $this->refreshCache($student);
        $this->logDeleted($student, $student->fullname);
    }


    public function restored(Student $student): void
    {
        $this->refreshCache($student);
        $this->logRestored($student, $student->fullname, 'students.show');
    }


    public function forceDeleted(Student $student): void
    {
        $this->refreshCache($student);
        $this->logDeleted($student, $student->fullname);
    }

    private function refreshCache(Student $student): void
    {
        Cache::forget("students:$student->school_id");
        $versionKey = "studentIndex:$student->school_id";
        Cache::increment($versionKey);
    }
}
