<?php

namespace App\Models;

use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassSubject extends Model
{
    use HasFactory, HasAuditFields;

    protected $table = 'classroom_subject';

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id', 'id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

}
