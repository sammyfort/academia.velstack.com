<?php

namespace App\Models;
use App\Observers\StaffClassObserver;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(StaffClassObserver::class)]
class StaffClassroomSubjectPermission extends Model
{
    //
    use HasFactory,HasAuditFields;
    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    public function classroom(): BelongsTo
    {
        return  $this->belongsTo(Classroom::class, 'classroom_id', 'id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function loggerName(): Attribute
    {
        return new Attribute(
            get: fn() => 'Staff Class Assignment',
        );
    }
}
