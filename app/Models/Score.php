<?php

namespace App\Models;

use App\Observers\ScoreObserver;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(ScoreObserver::class)]
class Score extends Model
{
    use HasFactory, HasAuditFields;
    protected $fillable = [
        'school_id',
        'class_id',
        'term_id',
        'student_id',
        'subject_id',
        'score_type_id',
        'score',
    ];
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function scoreType(): BelongsTo
    {
        return $this->belongsTo(ScoreType::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class, 'term_id');
    }

//    public function getTotalScoreAttribute(): float
//    {
//        return $this->class_score + $this->exam_score;
//    }

    /**
     * Check if a score of the same subject already exist for the student
     * in the same sem based on student_id, subject_id, and term_id.
     *
     * @param int $studentId
     * @param int $subjectId
     * @param int $termId
     * @return Score|null
     */
    public function findExistingScore(int $studentId, int $subjectId, int $termId): ?self
    {
        return $this->where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->where('term_id', $termId)
            ->first();
    }
}
