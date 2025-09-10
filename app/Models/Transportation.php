<?php

namespace App\Models;
use App\Observers\TransportationObserver;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(TransportationObserver::class)]
class Transportation extends Model
{
    //
    use HasFactory,HasAuditFields;
    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];


    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }
    public function fee(): BelongsTo
    {
        return $this->belongsTo(Fee::class);
    }

}
