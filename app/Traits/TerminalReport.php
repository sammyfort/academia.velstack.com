<?php

namespace App\Traits;

use App\Models\Score;

trait TerminalReport
{
    public function generateReportCard(int $termId, $classId): array
    {
        // Retrieve all scores for the student in the specified term
        $scores = $this->scores()
            ->where('term_id', $termId)
            ->with(['subject', 'scoreType'])
            ->where('class_id', $classId)
            ->get();

        if ($scores->isEmpty()) {
            abort(403, 'No records found for the selected academic term');
        }

        // Group scores by subject
        $subjects = $scores->groupBy('subject_id');

        // Prepare the report card
        $report = [];
        $totalScore = 0;
        $maximumTotalScore = 0;

        foreach ($subjects as $subjectId => $subjectScores) {
            $subjectName = $subjectScores->first()->subject->name;
            $className  = $subjectScores->first()->class;

            // Calculate total score for the subject
            $subjectTotal = $subjectScores->sum('score');
            $totalScore += $subjectTotal;

            $maxScoreForSubject = $subjectScores->sum(function ($score) {
                return $score->scoreType->max_score ?? 100; // Assuming max_score is defined in scoreType
            });

            $maximumTotalScore += $maxScoreForSubject;

            // Get the student's rank in this subject (class level)
            $classRank = $this->getSubjectPositionInClass($subjectId, $termId, $classId);

            // Get the student's rank in this subject (school level)
            $schoolRank = $this->getSubjectPositionInSchool($subjectId, $termId);
            $classAverage = $this->calculateClassAverage($subjectId, $termId, $classId);

            $report[] = [
                'class' => $className,
                'subject' => $subjectName,
                'score_details' => $subjectScores,
                'subject_total' => $subjectTotal,
                'class_rank' => $classRank,
                'class_average' => $classAverage,
                'school_rank' => $schoolRank,
            ];
        }

        // Calculate overall position in the class
        $classOverallRank = $this->getOverallPositionInClass($termId, $totalScore, $classId);

        // Calculate overall position in the school
        $schoolOverallRank = $this->getOverallPositionInSchool($termId, $totalScore);
        $overallPercentage = $maximumTotalScore > 0
            ? round(($totalScore / $maximumTotalScore) * 100, 1)
            : 0;

        $totalStudentsInClass = Score::where('term_id', $termId)
            ->where('class_id', $this->class_id)
            ->distinct('student_id')
            ->count('student_id');

        $totalStudentsInSchool = Score::where('term_id', $termId)
            ->whereHas('class', function ($query) {
                $query->where('group', $this->class->group);
            })
            ->distinct('student_id')
            ->count('student_id');

        return [
            'student' => $this,
            'term' => $termId,
            'class' => $scores->first()->class,
            'school' => $scores->first()->school,
            'subjects' => $report,
            'total_score' => $totalScore,
            'overall_percentage' => $overallPercentage,
            'class_overall_rank' => $classOverallRank,
            'school_overall_rank' => $schoolOverallRank,
            'total_students_in_class' => $totalStudentsInClass,
            'total_students_in_school' => $totalStudentsInSchool,
            'remark' => $this->currentTermRemark()->where('term_id', $termId)->first()
        ];
    }

    public function getSubjectPositionInClass(int $subjectId, int $termId, $class_id): int
    {
        // Retrieve all scores for the subject in the class
        $allScores = Score::where('subject_id', $subjectId)
            ->where('term_id', $termId)
            ->where('class_id', $class_id)
            ->selectRaw('student_id, SUM(score) as total_score')
            ->groupBy('student_id')
            ->orderByDesc('total_score')
            ->get();

        // Map scores to rank
        $rank = 1;
        $previousScore = null;
        $rankedScores = [];

        foreach ($allScores as $score) {
            if ($previousScore !== null && $score->total_score < $previousScore) {
                $rank += count($rankedScores[$previousScore]); // Adjust rank for ties
            }

            $rankedScores[$score->total_score][] = $rank;
            $previousScore = $score->total_score;
        }

        // Return the rank for the current student
        $studentTotalScore = $allScores->where('student_id', $this->id)->first()->total_score ?? 0;
        return $rankedScores[$studentTotalScore][0] ?? count($allScores) + 1; // Default to "last rank + 1" if not found
    }


    protected function calculateClassAverage(int $subjectId, int $termId, $class_id): float
    {
        // Retrieve all students' scores for the same subject and term, based on the class in the score table
        $classScores = $this->school->students()->whereHas('scores', function ($query) use ($subjectId, $termId) {
            $query->where('subject_id', $subjectId)
                ->where('term_id', $termId)
                ->whereNotNull('class_id'); // Ensure scores have an associated class_id
        })
            ->with(['scores' => function ($query) use ($subjectId, $termId) {
                $query->where('subject_id', $subjectId)
                    ->where('term_id', $termId)
                    ->whereNotNull('class_id'); // Same filter for the scores
            }])
            ->get()
            ->flatMap(function ($student) use ($class_id) {
                // Filter out scores that belong to the current student's class
                return $student->scores->where('class_id', $class_id)->pluck('score');
            });

        // Calculate average
        return $classScores->count() > 0
            ? round($classScores->sum() / $classScores->count(), 1)
            : 0.0;
    }


    public function getOverallPositionInClass(int $termId, float $totalScore, $class_id): int
    {
        // Get all students with their total scores for the class
        $students = Score::query()
            ->where('class_id', $class_id)
            ->where('term_id', $termId)
            ->selectRaw('student_id, SUM(score) as total_score')
            ->groupBy('student_id')
            ->orderByDesc('total_score')
            ->get();

        $totalStudents = $students->count();

        // Assign ranks with tie handling
        $rank = 1;
        $ranks = [];
        $prevScore = null;

        foreach ($students as $student) {
            if ($prevScore !== null && $student->total_score < $prevScore) {
                $rank = count($ranks) + 1;
            }
            $ranks[$student->student_id] = $rank;
            $prevScore = $student->total_score;
        }

        // Return this student's actual rank or default to last place
        $studentRank = $ranks[$this->id] ?? $totalStudents;
        return min($studentRank, $totalStudents); // Ensure rank never exceeds total students
    }





    public function getSubjectPositionInSchool(int $subjectId, int $termId): int
    {
        // Retrieve the class group for the current student's class
        $classGroup = $this->class->group;

        // Retrieve all scores for the subject in the school, filtered by class group
        $allScores = Score::where('subject_id', $subjectId)
            ->where('term_id', $termId)
            ->whereHas('class', function ($query) use ($classGroup) {
                $query->where('group', $classGroup);
            })
            ->selectRaw('student_id, SUM(score) as total_score')
            ->groupBy('student_id')
            ->orderByDesc('total_score')
            ->get();

        // Map scores to rank
        $rank = 1;
        $previousScore = null;
        $rankedScores = [];

        foreach ($allScores as $score) {
            if ($previousScore !== null && $score->total_score < $previousScore) {
                $rank += count($rankedScores[$previousScore]); // Adjust rank for ties
            }

            $rankedScores[$score->total_score][] = $rank;
            $previousScore = $score->total_score;
        }

        // Return the rank for the current student
        $studentTotalScore = $allScores->where('student_id', $this->id)->first()->total_score ?? 0;
        return $rankedScores[$studentTotalScore][0] ?? count($allScores) + 1; // Default to "last rank + 1" if not found
    }

    public function getOverallPositionInSchool(int $termId, float $totalScore): int
    {
        // Retrieve the class group for the current student's class
        $classGroup = $this->class->group;

        // Retrieve all scores for the term in the school, filtered by class group
        $students = Score::query()
            ->where('school_id', $this->school_id)
            ->where('term_id', $termId)
            ->whereHas('class', function ($query) use ($classGroup) {
                $query->where('group', $classGroup);
            })
            ->selectRaw('student_id, SUM(score) as total_score')
            ->groupBy('student_id')
            ->orderByDesc('total_score')
            ->get();

        $rank = $students->pluck('total_score')->search($totalScore) + 1;
        return $rank ?: count($students) + 1;
    }







}
