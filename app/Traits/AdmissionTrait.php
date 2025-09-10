<?php

namespace App\Traits;

use App\Models\Classroom;
use App\Models\School;
use App\Models\Student;
use App\Notifications\NotifyParentSchoolOfAdmission;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\Notification;

trait AdmissionTrait
{
    /**
     * @throws Exception
     */



    /**
     * @throws Exception
     */
    public function throwStudentBreachException(string $mailTo, Student $foundStudent, School $newSchool)
    {
        Notification::route('mail', $mailTo)->notify(new NotifyParentSchoolOfAdmission($foundStudent, $newSchool));
        throw new Exception("Student has debt with previous school and cannot be admitted here.
            Previous school has been notified of this admission.");
    }
}
