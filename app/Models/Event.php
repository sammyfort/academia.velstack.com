<?php

namespace App\Models;
use App\Enum\NotifyClass;
use App\Observers\EventObserver;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(EventObserver::class)]
class Event extends Model
{
    //
    use HasFactory,HasAuditFields;
    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',

        ];
    }

    public function trashInfo(): Attribute
    {
        return new Attribute(
            get: fn() => "$this->name | $this->start_date"
        );
    }

    public function recipients(): string
    {
        return match ($this->send_notification_to) {
            NotifyClass::STAFF->value => $this->school->staff()->pluck('phone')->implode(','),
            NotifyClass::PARENTS->value => $this->school->parents()->pluck('phone')->implode(','),
            NotifyClass::STUDENTS->value => $this->school->students()->pluck('phone')->implode(','),
            NotifyClass::ALL->value => collect([
                $this->school->staff()->pluck('phone'),
                $this->school->parents()->pluck('phone'),
                $this->school->students()->pluck('phone'),
            ])->flatten()->unique()->implode(','),
            default => '',
        };
    }

}
