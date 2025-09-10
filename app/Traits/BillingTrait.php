<?php

namespace App\Traits;



use App\Models\Fee;
use App\Models\Student;
use App\Models\Term;


trait BillingTrait
{
    public function createBill(Student $student, Fee $fee, Term $term): void
    {
        school()->bills()->create([
            'student_id' => $student->id,
            'fee_id' => $fee->id,
            'amount' => $fee->amount,
            'term_id' => $term->id,
        ]);
    }


}
