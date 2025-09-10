<?php

namespace App\Models;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Preference extends Model
{
    //
    use HasFactory,HasAuditFields;
    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    protected function casts():array
    {
        return [
            'show_class_average' => 'boolean',
            'show_overall_position' => 'boolean',
            'show_overall_percentage' => 'boolean',

            'show_student_image_on_report' => 'boolean',
            'show_school_image_on_report' => 'boolean',
        ];
    }
}
