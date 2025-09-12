<?php

namespace App\Models;


use App\Observers\ClassroomObserver;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\SlugOptions;
use Spatie\Sluggable\HasSlug;


#[ObservedBy(ClassroomObserver::class)]
class Classroom extends Model
{
    use HasFactory,HasSlug, HasAuditFields, SoftDeletes;
    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');

    }
    public function name(): Attribute
    {
        return new Attribute(
            get: fn($value) => strtoupper($value),
        );
    }

    public function trashInfo(): Attribute
    {
        return new Attribute(
            get: fn() => "$this->name| $this->slug"
        );
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function reportRemarks(): HasMany
    {
        return $this->hasMany(ReportRemark::class, 'class_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id', 'id')
            ->where('is_completed', '=',false);
    }

    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class, 'class_id');
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(
            Subject::class,
            ClassSubject::class,
            'class_id',
            'subject_id'
        )->withTimestamps();
    }

    public function scoreTypes(): HasMany
    {
        return $this->hasMany(ScoreType::class, 'class_id', 'id');
    }

    public function subjectScoreTypes(): HasMany
    {
        return $this->hasMany(SubjectScoreType::class, 'class_id');
    }

    public function hasSubject($subject_id): bool
    {
      return $this->subjects()->where('subject_id', $subject_id)->exists();
    }

    public function getClassRankingForTerm(int|null $term_id, string $search = ''): array
    {
        if (!$term_id) return [];
        $studentsQuery = $this->students();
        if (!empty($search)) $studentsQuery = $studentsQuery->search(null, null, $search);
        $students = $studentsQuery->with(['scores' => function($query) use ($term_id) {
            $query->where('term_id', $term_id)->where('class_id', $this->id)->with('subject', 'scoreType');
        },'currentTermRemark' => function ($query) use ($term_id) {
        $query->where('term_id', $term_id);
       }])->get();
        $rankings = $students->map(function (Student $student) use ($term_id) {
            $scores = $student->scores;
            return [
                'student' => $student,
                'scores' => $scores,
                'total_score' => $scores->sum('score'),
            ];
        });
        $sortedRankings = $rankings->sortByDesc('total_score')->values();
        $rank = 1; $prevScore = null; $sameRankCount = 0;
        return $sortedRankings->map(function ($item, $index) use (&$rank, &$prevScore, &$sameRankCount) {
            if ($prevScore !== null && $item['total_score'] < $prevScore) {
                $rank += $sameRankCount + 1;
                $sameRankCount = 0;
            } else if ($prevScore === $item['total_score']) {
                $sameRankCount++;
            }
            $prevScore = $item['total_score'];
            $item['rank'] = $rank;
            return $item;
        })->all();
    }
    /**
     * Get all staff teaching in this class
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(
            Staff::class,
            'class_staff',
            'class_id',
            'staff_id'
        );
    }


    /**
     * Get all class teachers (masters) managing this class
     */
    public function classTeacher()
    {
        return $this->belongsToMany(Staff::class, 'class_teacher', 'class_id', 'staff_id')
            ->using(ClassTeacher::class)
            ->withPivot('role')
            ->withTimestamps();
    }


}
