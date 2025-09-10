<?php

namespace App\Models;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    //
    use HasFactory,HasAuditFields;
    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];

    public function commentable(): MorphTo
    {
        return $this->morphTo('commentable');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->commentable()->getModel(), 'commentable_id', 'id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }
}
