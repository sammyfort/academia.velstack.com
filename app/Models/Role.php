<?php

namespace App\Models;
use App\Traits\HasAuditFields;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as SpatieRole;
class Role extends SpatieRole
{
    //
   // use HasFactory,HasAuditFields, SoftDeletes;
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





}
