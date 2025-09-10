<?php

namespace App\Models;

use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Communication extends Model
{
    use HasFactory, HasAuditFields;
    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    protected function casts(): array
    {
        return [
            'send_after_payment' => 'boolean',
            'send_upcoming_events' => 'boolean',
            'send_student_attendance' => 'boolean',
            'send_admission_alert' => 'boolean',
        ];
    }
}
