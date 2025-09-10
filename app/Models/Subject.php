<?php

namespace App\Models;

use App\Observers\SubjectObserver;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

#[ObservedBy(SubjectObserver::class)]
class Subject extends Model
{
    use HasFactory, HasAuditFields, SoftDeletes;

    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class)
            ->using(StudentSubject::class)
            ->withPivot(['class_id'])
            ->whereHas('school', function ($query) {
                $query->where('schools.id', '=', DB::raw('students.school_id'));
            })
            ->withTimestamps();
    }

    public function trashInfo(): Attribute
    {
        return new Attribute(
            get: fn() => "$this->name | $this->code"
        );
    }

    public function name(): Attribute
    {
        return new Attribute(
            get: fn($value) => strtoupper($value),
        );
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class,
            'classroom_subject',
            'subject_id',
            'class_id' )->withTimestamps();
    }

    public function scoreTypes(): BelongsToMany
    {
        return $this->belongsToMany(
            ScoreType::class,
            'subject_score_types',
            'subject_id',
            'score_type_id'
        )->withPivot('class_id', 'term_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class,);
    }

    public function teachers():BelongsToMany
    {
        return $this->belongsToMany(Staff::class, 'subject_staff');
    }

}
