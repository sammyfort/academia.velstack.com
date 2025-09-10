<?php

namespace App\Traits;


use App\Models\Staff;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait HasAuditFields
{
    protected static function bootHasAuditFields(): void
    {
        static::creating(function ($model) {
            $model->created_by = Auth::id() ?? null;
            $model->uuid = strtoupper(Str::uuid());
        });

    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    public function createdByName() : Attribute {
        return new Attribute(
            get: fn() => $this->created_by ? $this->createdBy->full_name : '',
        );
    }


    protected static function message($event, $model): string
    {
        $auth = Auth::user();
        return match ($event) {
            'created' => Str::ucfirst($event) . ' created.',
            'updated' => "$auth->fullname made changes ". get_class($model),
            'restored' => "$auth->fullname made restored $model",
        };
    }

    public static function generateIncreasingNumber($length = 6, $withTimestamp = false): string
    {
        $count = self::count();
        $next = $count + 1;
        return $withTimestamp ?
            now()->format('dmy'). str_pad($next, $length, '0', STR_PAD_LEFT)
            : str_pad($next, $length, '0', STR_PAD_LEFT) ;
    }

    public function getSearchableFields(): array
    {
        $fields = Schema::getColumnListing($this->getTable());
        return array_filter($fields, function ($field) {
            return !in_array($field, ['password', 'remember_token']);
        });
    }


}
