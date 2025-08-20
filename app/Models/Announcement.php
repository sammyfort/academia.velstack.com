<?php

namespace App\Models;

use App\Traits\BootModelTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $uuid
 * @property int $created_by_id
 * @property string $created_at
 * @property string $updated_at
 * @property boolean $is_active
 */
class Announcement extends Model
{
    //
    use BootModelTrait;

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
