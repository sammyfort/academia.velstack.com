<?php

namespace App\Models;
use App\Traits\HasAuditFields;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payslip extends Model
{
    //
    use HasFactory,HasAuditFields;
    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function scopeForMonth($query, $date)
    {
        $date = Carbon::parse($date);
        return $query->whereYear('date', $date->format('Y'))
            ->whereMonth('date', $date->format('m'));
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];

    }
}
