<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enum\Gender;
use App\Traits\PasswordReset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, PasswordReset;
    protected $fillable = [
        'fullname',
        'email',
        'password',
    ];

    public function profile(): MorphOne
    {
        return $this->morphOne(Profile::class, 'profileable');
    }
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function replies(): MorphMany
    {
        return $this->morphMany(Reply::class, 'replyable');
    }

    public function image(): string
    {

        $fallback = $this->gender === Gender::MALE->value ? 'm_default.png' : 'm_default.png';
        return Storage::disk('public')->url("images/$fallback");
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
