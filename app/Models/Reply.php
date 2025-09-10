<?php

namespace App\Models;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reply extends Model
{
    //
    use HasFactory,HasAuditFields;
    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function replyable(): MorphTo
    {
        return $this->morphTo('replyable');
    }


    public function user(): BelongsTo
    {

        return $this->belongsTo($this->replyable()->getModel(), 'replyable_id', 'id');
    }
}
