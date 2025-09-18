<?php

namespace App\Services;

use App\Models\Classroom;
use Illuminate\Support\Str;

class ClassService
{
   public function markAttendance(Classroom $classroom): string
   {
       $prefix = strtoupper(substr($classroom->name, 0, 3));
       $uniquePart = Str::upper(Str::random(5));
       return $prefix . '-' . $uniquePart;
   }

}
