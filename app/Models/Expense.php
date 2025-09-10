<?php

namespace App\Models;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    //
    use HasFactory,HasAuditFields;
    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);

    }

    public function totalAmount(): Attribute
    {
        return new Attribute(
            get: fn() => number_format($this->amount, 2),
        );
    }
    protected function casts(): array
    {
        return [
            'expense_date' => 'date',
        ];
    }
}
