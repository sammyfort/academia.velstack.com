<?php

namespace App\Models;

use App\Observers\FeeObserver;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;


#[ObservedBy(FeeObserver::class)]
class Fee extends Model
{
    use HasFactory, HasAuditFields;
    protected $fillable = ['name', 'amount', 'type', 'ref_id', 'term_id', 'class_id', 'student_id'];

    public function name(): Attribute
    {
        return new Attribute(
          get: fn($value) => ucwords($value),
            set: fn($value) => ucwords($value)
        );
    }

    public function trashInfo(): Attribute
    {
        return new Attribute(
            get: fn() => "$this->name - $this->amount",
        );
    }
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class, 'term_id', 'id');
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class, 'fee_id', 'id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(PaymentBill::class, Bill::class);
    }


    public function studentsWithDebts(int $schoolId = null): Collection
    {
        return Student::whereHas('bills', function ($query) {
            $query->where('fee_id', $this->id)
                ->where(function ($query) {
                    $query->whereRaw('(SELECT COALESCE(SUM(payment_bills.amount_paid), 0)
                    FROM payment_bills
                    WHERE payment_bills.bill_id = bills.id) < bills.amount')
                        ->orWhereRaw('(SELECT COUNT(*)
                    FROM payment_bills
                    WHERE payment_bills.bill_id = bills.id) = 0');
                });
        })
            ->when($schoolId, function ($query) use ($schoolId) {
                $query->where('school_id', $schoolId);
            })
            ->with(['bills' => function ($query) {
                $query->where('fee_id', $this->id)
                    ->with('fee')
                    ->whereRaw('(SELECT COALESCE(SUM(payment_bills.amount_paid), 0)
                FROM payment_bills
                WHERE payment_bills.bill_id = bills.id) < bills.amount')
                    ->orWhereRaw('(SELECT COUNT(*)
                FROM payment_bills
                WHERE payment_bills.bill_id = bills.id) = 0');
            }])
            ->get();
    }

    public function studentsWithDebtsRelation(): HasManyThrough
    {
        return $this->hasManyThrough(
            Student::class,   // Final model: Student
            Bill::class,      // Intermediate model: Bill
            'fee_id',         // Foreign key on bills that references fees.id
            'id',             // *This parameter is not used directly* – see explanation below
            'id',             // Local key on fees (Fee.id)
            'student_id'      // Foreign key on bills that references students.id
        )->where(function ($query) {
            $query->whereRaw('(SELECT COALESCE(SUM(payment_bills.amount_paid), 0)
                    FROM payment_bills
                    WHERE payment_bills.bill_id = bills.id) < bills.amount')
                ->orWhereRaw('(SELECT COUNT(*)
                    FROM payment_bills
                    WHERE payment_bills.bill_id = bills.id) = 0');
        });
    }

    public function paymentBills(): HasManyThrough
    {
        return $this->hasManyThrough(
            PaymentBill::class, // Final model: PaymentBill
            Bill::class,        // Intermediate model: Bill
            'fee_id',           // Foreign key on the bills table referencing fees.id
            'bill_id',          // Foreign key on the payment_bills table referencing bills.id
            'id',               // Local key on the fees table (fees.id)
            'id'                // Local key on the bills table (bills.id)
        );
    }


    public function transportation(): BelongsTo
    {
        return $this->belongsTo(Transportation::class, 'ref_id', 'id');
    }


}
