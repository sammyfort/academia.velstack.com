<?php

namespace App\Models;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LentBook extends Model
{
    //
    use HasFactory,HasAuditFields;
    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'lentable_id', 'id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'lentable_id', 'id');
    }

    public function lentable(): MorphTo
    {
        return $this->morphTo('lentable', 'lentable_type', 'lentable_id');
    }

    protected function casts()
    {
        return [
          'lent_on' => 'datetime',
          'return_on' => 'datetime',
          'due_on' => 'datetime',
        ];

    }
}
