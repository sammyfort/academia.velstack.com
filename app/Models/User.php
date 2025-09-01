<?php

namespace App\Models;

 use App\Notifications\PasswordResetNotification;
 use App\Observers\UserObserver;
 use App\Traits\BootModelTrait;
 use Filament\Models\Contracts\FilamentUser;
 use Illuminate\Contracts\Auth\MustVerifyEmail;
 use Illuminate\Database\Eloquent\Attributes\ObservedBy;
 use Illuminate\Database\Eloquent\Casts\Attribute;
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Relations\BelongsTo;
 use Illuminate\Database\Eloquent\Relations\HasMany;
 use Illuminate\Database\Eloquent\Relations\HasManyThrough;
 use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
 use Illuminate\Support\Carbon;
 use Spatie\MediaLibrary\HasMedia;
 use Spatie\MediaLibrary\InteractsWithMedia;
 use Filament\Panel;
 use Spatie\Permission\Traits\HasRoles;
 use Filament\Models\Contracts\HasName;

 /**
 * @property string $id
 * @property string $uuid
 * @property string $firstname
 * @property string $lastname
 * @property string $fullname
 * @property string $mobile
 * @property string $email
 * @property string $facebook
 * @property string $x
 * @property string $instagram
 * @property string $linkedin
 * @property string $email_verified_at
 * @property string $password
 * @property bool $is_active
 * @property string $last_login
 * @property int $created_by_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $initials
 * @property string $google_id
 * @property string $referral_code
 * @property string $referral_link
 * @property int $points
 * @property bool $is_referrer_points_settled
 */

 #[ObservedBy(UserObserver::class)]
 class User extends Authenticatable implements MustVerifyEmail, HasMedia, FilamentUser, HasName
{
    use HasFactory, Notifiable, \Illuminate\Auth\MustVerifyEmail,
        BootModelTrait, InteractsWithMedia, HasRoles;

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_referrer_points_settled' => 'boolean'
    ];
    protected $appends = [
        'initials', 'fullname', 'avatar', 'created_at_str', 'referral_link', 'points_in_cedis'
    ];

     public function registerMediaCollections(): void
     {
         $this->addMediaCollection('avatar')
             ->singleFile();
     }

     public function referrer(): BelongsTo
     {
         return $this->belongsTo(self::class, 'referrer_id');
     }

     public function country(): BelongsTo
     {
         return $this->belongsTo(Country::class, 'country_id');
     }

     public function referrals(): HasMany
     {
         return $this->hasMany(self::class, 'referrer_id');
     }

    public function fullname(): Attribute
    {
        return Attribute::make(fn () => "$this->lastname $this->firstname");
    }

    public function initials(): Attribute
    {
        return Attribute::make(fn () => $this->lastname[0] . $this->firstname[0]);
    }

     public function pointsInCedis(): Attribute
     {
         return Attribute::make(
             get: fn () => $this->points * config('app.point_cedi_rate')
         );
     }

    public function lastLogin(): Attribute
    {
        return Attribute::make(fn ($value) => Carbon::parse($value)->format(dateFormat(true)));
    }

    public function avatar(): Attribute
    {
        return Attribute::make(fn () => $this->getFirstMedia('avatar'));
    }

    public function referralLink(): Attribute
    {
        return Attribute::make(fn () => route('register')."?rfc=".$this->referral_code);
    }

    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'user_id', 'id');
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

     public function signboards(): HasManyThrough
     {
         return $this->hasManyThrough(
             Signboard::class,
             Service::class,
             'user_id',
             'service_id',
             'id',
             'id'
         );
     }

     public function canAccessPanel(Panel $panel): bool
     {
         return $this->hasRole('admin');
     }

     public function getFilamentName(): string
     {
         return "{$this->firstname} {$this->lastname}";
     }

 }
