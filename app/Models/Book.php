<?php

namespace App\Models;
use App\Observers\BookObserver;
use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(BookObserver::class)]
class Book extends Model
{
    //
    use HasFactory,HasAuditFields;
    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
    public function lentBooks(): HasMany
    {
        return $this->hasMany(LentBook::class, 'book_id', 'id');
    }

    public function loggerName(): Attribute
    {
        return new Attribute(
            get:fn() => 'Book',
        );
    }


    public function trashInfo(): Attribute
    {
        return new Attribute(
            get: fn() => "$this->title | $this->author"
        );
    }


    public function lentCopies(): Attribute
    {
        return new Attribute(
            get: fn () => $this->lentBooks()->whereNull('returned_on')->count()
        );
    }

    public function remainingCopies(): Attribute
    {
        return new Attribute(
            get: fn () => $this->copies - $this->lentBooks()->whereNull('returned_on')->count()
        );
    }

    public function scopeTotalRemainingCopies($query)
    {
        return $query->withCount(['lentBooks as lent_copies' => function ($query) {
            $query->whereNull('returned_on');
        }])->get()->sum(fn($book) => $book->copies - $book->lent_copies);
    }



}
