<?php

namespace App\Models;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    //
    use HasFactory,HasAuditFields;
    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];

    public function staff(): MorphTo
    {
        return $this->morphTo();

    }

    protected static function booted(): void
    {
        static::creating(function ($bank) {
            $bank->school_id = school()->id;
        });
    }
}
