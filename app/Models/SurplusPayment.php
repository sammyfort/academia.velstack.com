<?php

namespace App\Models;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurplusPayment extends Model
{
    //
    use HasFactory,HasAuditFields, SoftDeletes;
    protected $guarded = ['id', 'uuid', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }


}
