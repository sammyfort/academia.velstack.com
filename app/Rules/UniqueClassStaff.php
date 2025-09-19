<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
class UniqueClassStaff implements ValidationRule
{
     public function __construct(protected int $classId)
    {
       
    }


    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
         $exists = DB::table('staff_classroom_subject_permissions')
            ->where('classroom_id', $this->classId)
            ->where('staff_id', $value)
            ->whereNull('subject_id')
            ->exists();

        if ($exists) {
            $fail("This staff member is already assigned as a class teacher for this class.");
        }
    }
}
