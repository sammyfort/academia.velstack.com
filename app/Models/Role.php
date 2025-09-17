<?php

namespace App\Models;
use App\Traits\HasAuditFields;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Builder;
class Role extends SpatieRole
{
    //
  //  use HasAuditFields;
   // protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];

     public function school(): BelongsTo
     {
         return $this->belongsTo(School::class);
     }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($role) {
            if ($role->name === 'Super Administrator') {
                throw new Exception('Super Admin role cannot be deleted.');
            }

            $role->users()->detach();
        });
    }

    public function getSearchableFields(): array
    {
        $fields = Schema::getColumnListing($this->getTable());
        return array_filter($fields, function ($field) {
            return !in_array($field, ['password', 'remember_token']);
        });
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        $fields = $this->getSearchableFields();

        return $query->where(function ($q) use ($fields, $term) {
            foreach ($fields as $field) {
                $q->orWhere($field, 'like', "%{$term}%");
            }
        });
    }



}
