<?php

namespace App\Models;

use App\Observers\BillObserver;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

#[ObservedBy(BillObserver::class)]
class Bill extends Model
{
    use HasFactory, HasAuditFields;
    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];



    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function fee(): BelongsTo
    {
        return $this->belongsTo(Fee::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('amount', 'like', '%' . $search . '%')
                        ->orWhere('cycle', 'like', '%' . $search . '%');
                });
                $query->orWhereHas('term', function ($termQuery) use ($search) {
                    $termQuery->where('name', 'like', '%' . $search . '%');
                });

                $query->orWhereHas('fee', function ($feeQuery) use ($search) {
                    $feeQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('amount', 'like', '%' . $search . '%');
                });
            });
    }


    public function payments(): HasMany
    {
        return $this->hasMany(PaymentBill::class, 'bill_id', 'id');
    }

    /**
     * Get total amount paid for a particular fee by a student
     * @return float
     */
    public function totalPaid(): float
    {
        return $this->payments()->sum('amount_paid');
    }


    /**
     * Get all debts for this fee
     * @param $query
     * @return mixed
     */
    public function scopeOutstanding($query): mixed
    {
        return $query->where(function ($query) {
            $query->whereDoesntHave('payments')
            ->orWhereRaw('amount > (
                SELECT COALESCE(SUM(amount_paid), 0)
                FROM payment_bills
                WHERE payment_bills.bill_id = bills.id
            )');
        });
    }


    protected function balance(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->amount - ($this->payments->sum('amount_paid'))
        );
    }
}
