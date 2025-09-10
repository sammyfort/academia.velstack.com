<?php

namespace App\Models;

use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory, HasAuditFields;
   protected $fillable = [
       'email_address',
       'phone_number',
       'gender',
       'dob',
       'qualification',
       'experience',
       'religion',
       'region',
       'city',
       'bio',
       'image'

   ];

    public function profileable(): MorphTo
    {
        return $this->morphTo();
    }
}
