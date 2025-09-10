<?php

namespace App\Models;

use App\Enum\TermStatus;
use App\Observers\TermObserver;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(TermObserver::class)]
class Term extends Model
{
    use HasFactory, HasAuditFields, SoftDeletes;

    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];

    public function trashInfo(): Attribute
    {
        return new Attribute(
            get: fn() => "$this->name   | $this->start_date"
        );
    }
    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class);
    }

    protected static function booting(): void
    {
        parent::booting();
        static::deleting(function ($term) {
            if ($term->fees->isNotEmpty()) {
                abort(403,'You cannot delete this academic calender because it has associated fees');
            }
        });
    }

    public function reportRemarks(): HasMany
    {
        return $this->hasMany(ReportRemark::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);

    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function hasEnded(): bool
    {
        return $this->status === TermStatus::ENDED->value;
    }
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'reporting' => 'boolean',
            'next_term_begins' => 'date',
        ];
    }
}
