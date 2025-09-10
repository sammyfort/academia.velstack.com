<?php

namespace App\Models;
use App\Enum\AllowanceType;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AllowanceAndDeduction extends Model
{
    //
    use HasFactory,HasAuditFields;
    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];


    public function staff()
    {
        return $this->belongsToMany(
            Staff::class, StaffAllowanceDeduction::class, 'allowance_id', 'staff_id')
            ->withPivot('applied_at')
            ->withTimestamps();
    }



    public function getType(): Attribute
    {
        return new Attribute(
            get: fn () => ucfirst($this->type),
        );
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function scopeAllowance($query)
    {
        return $query->where('type', AllowanceType::ALLOWANCE->value);
    }
}
