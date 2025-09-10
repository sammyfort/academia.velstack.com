<?php

namespace App\Models;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    //
    use HasFactory,HasAuditFields;
    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];

    public function attendable(): MorphTo
    {
        return $this->morphTo('attendable');
    }

    public function casts(): array
    {
        return [
            'present' => 'boolean',
            'date' => 'date',
        ];
    }
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }


    public function scopeFilter($query, $date, $byDay = false)
    {
        $query->whereDate('date', $date->format('Y-m-d'));

        if ($byDay) {
            $query->whereDay('date', $date->format('Y-m-d'));
        }

        return $query;
    }

    public function scopePresent($query, $term_id)
    {
        return $query->where('term_id', $term_id)->where('present', true);
    }
}
