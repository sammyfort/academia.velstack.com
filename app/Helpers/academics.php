<?php

use App\Enum\ClassLevel;
use App\Models\Score;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

if (! function_exists('getClassAveragesForTerm')) {
    function getClassAveragesForTerm(int $termId): array
    {
        $classes = school()->classes;

        // Get all scores for the term in a single query
        $scores = Score::select('class_id', DB::raw('ROUND(AVG(score), 1) as average'))
            ->where('term_id', $termId)
            ->whereNotNull('class_id')
            ->groupBy('class_id')
            ->pluck('average', 'class_id')
            ->toArray();

        $classAverages = $classes->mapWithKeys(function ($class) use ($scores) {
            return [$class->name => $scores[$class->id] ?? 0.0];
        });

        return $classAverages->toArray();

    }

    if (! function_exists('getClassAveragesByTypeForTerm')) {
        function getClassAveragesByTypeForTerm(int $termId): array
        {
            $classes = school()->classes->groupBy(fn($class) => ClassLevel::from($class->level));

            // Fetch class-wise average scores using a single query
            $scores = Score::select('class_id', DB::raw('ROUND(AVG(score), 1) as average'))
                ->where('term_id', $termId)
                ->whereNotNull('class_id')
                ->groupBy('class_id')
                ->pluck('average', 'class_id')
                ->toArray();

            $classAveragesByType = [];

            foreach ($classes as $type => $typeClasses) {
                // Map class IDs to their average scores
                $classAverages = $typeClasses->mapWithKeys(fn($class) => [
                    $class->name => $scores[$class->id] ?? 0.0
                ]);

                // Calculate the average score for the entire class type
                $typeAverage = $classAverages->count() > 0
                    ? round($classAverages->sum() / $classAverages->count(), 1)
                    : 0.0;

                // Store results
                $classAveragesByType[$type] = $typeAverage;
            }

            return $classAveragesByType;
        }

    }
}

if (! function_exists('hasOtherClassesInGroup')) {
    function hasOtherClassesInGroup($classId): bool
    {
        $classGroup = \App\Models\Classroom::find($classId)?->group;

        if ($classGroup) {
            $classCount = \App\Models\Classroom::where('group', $classGroup)->count();
            return $classCount > 1; // True if there are multiple classes in the same group
        }

        return false;
    }
}


if (! function_exists('getStudentAveragesAcrossTerms')) {
    function getStudentAveragesAcrossTerms(int $studentId): array
    {
        $student = Student::query()->find($studentId);

        if (!$student) {
            return [];
        }

        // Get the scores across all terms for the student
        $studentScores = $student->scores()
            ->with('term')  // Make sure we load the term relationship
            ->get()
            ->groupBy('term_id') // Group by term_id
            ->mapWithKeys(function ($scores, $termId) use($student) {
                $averageScore = $scores->avg('score'); // Calculate average score
                $term = $student->school->terms()->find($termId);  // Get term by ID

                if ($term) {
                    return [$term->name => round($averageScore, 1)]; // Return term name and average score
                }

                return [];
            });

        return $studentScores->toArray(); // Ensure it's converted to an array
    }
}




