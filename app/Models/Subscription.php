<?php

namespace App\Models;

use App\Observers\SubscriptionObserver;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(SubscriptionObserver::class)]
class Subscription extends Model
{
    use HasFactory, HasAuditFields;

    protected  $fillable = [
      'school_id',
      'expires_at',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    protected function casts(): array
    {
        return [
          'expires_at' => 'date'
        ];
    }
}
