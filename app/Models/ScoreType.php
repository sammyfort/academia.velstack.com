<?php

namespace App\Models;

use App\Observers\ScoreTypeObserver;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(ScoreTypeObserver::class)]
class ScoreType extends Model
{
    use HasFactory, HasAuditFields;

    protected $fillable = [
        'school_id',
        'uuid',
        'name',
        'percentage'
    ];

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(
            Subject::class,
            'subject_score_types',
            'score_type_id',
            'subject_id'
        )->withPivot('class_id', 'term_id');
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id', 'id');

    }

    public function loggerName(): Attribute
    {
        return new Attribute(
            get: fn() => 'Score Type',
        );
    }

    public function name(): Attribute
    {
        return new Attribute(
            get: fn($value) => strtoupper($value),
        );
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    protected static function booting(): void
    {
        parent::booting();
        static::deleting(function ($model) {
            if ($model->scores->count() > 0) {
                abort(403,"Score type has scores and cannot be deleted");
            }
        });
    }
}
