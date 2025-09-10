<?php

namespace App\Models;


use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, HasAuditFields;
    protected $fillable = [
        'school_id',
        'type',
        'amount',
        'reference',
        'channel',
        'status',
        'response',
        'payment_number'
    ];


    public function school():BelongsTo
    {
        return $this->belongsTo(School::class);
    }


}
