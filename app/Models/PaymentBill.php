<?php

namespace App\Models;
use App\Observers\PaymentBillObserver;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


#[ObservedBy(PaymentBillObserver::class)]
class PaymentBill extends Model
{
    //
    use HasFactory,HasAuditFields;
    protected $guarded = ['id', 'uuid','updated_at', 'deleted_at', 'deleted_by', 'created_by'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }
}
