<?php

namespace App\Models;


use App\Observers\PaymentObserver;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[ObservedBy(PaymentObserver::class)]
class Payment extends Model
{
    use HasFactory,HasAuditFields;
    protected $guarded = ['id', 'uuid',  'updated_at', 'deleted_at', 'deleted_by', 'created_by'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function channel(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucwords($value)
        );
    }

    public function bills(): HasMany
    {
        return $this->hasMany(PaymentBill::class, 'payment_id', 'id')
            ->whereHas('bill');
    }

    public function scopeSearch($query, $search)
    {
        return $query
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    foreach ($this->getSearchableFields() as $field) {
                        $subQuery->orWhere($field, 'like', "%$search%");
                    }
                });
                $query->orWhereHas('student', function ($termQuery) use ($search) {
                    $termQuery->search($search);
                });

            });
    }

    protected static function booted(): void
    {
        parent::boot();
        static::creating(function ($payment) {
            $payment->uuid = self::generateIncreasingNumber(6, true);
        });
    }


}
