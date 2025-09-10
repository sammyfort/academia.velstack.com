<?php

namespace App\Models;

use App\Observers\ParentObserver;
use App\Observers\StaffObserver;
use App\Traits\HasAuditFields;
use App\Traits\PasswordReset;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[ObservedBy(ParentObserver::class)]

class _Parent extends Authenticatable
{
    protected string $guard = 'parent';
    use HasFactory, HasAuditFields, PasswordReset;

    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            Student::class,
            ParentStudent::class,
            'parent_id',
            'student_id')
            ->withTimestamps();
    }

    protected static function booting(): void
    {
        parent::booting();
        static::deleting(function ($parent) {
           if ($parent->students->count() > 0) {
               abort(403,'You cannot delete this parent because it has children.
                Consider updating children with this parent or delete children ');
           }
        });
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function loggerName(): Attribute
    {
        return new Attribute(
            get: fn() => 'Parent',
        );
    }

    public function trashInfo(): Attribute
    {
        return new Attribute(
            get: fn() => "$this->name - $this->phone",
        );
    }

    protected function casts(): array
    {
        return [
            'suspended' => 'boolean',
        ];
    }
}
