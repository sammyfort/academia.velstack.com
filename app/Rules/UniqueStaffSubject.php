<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueStaffSubject implements ValidationRule
{
      public function __construct(protected int|null $classId, protected int|null $staffId)
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
            ->where('staff_id', $this->staffId)
            ->where('subject_id', $value)
            ->exists();

        if ($exists) {
            $subject = school()->subjects()->find($value);
            $name = $subject->name ?? $value;
            $fail("This staff member is already assigned to subject {$name} in this class.");
        }
    }
}
