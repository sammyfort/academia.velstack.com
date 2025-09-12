<?php

namespace App\Models;


use App\Enums\AllowanceType;
use App\Enums\Gender;
use App\Observers\StaffObserver;
use App\Traits\HasAuditFields;
use App\Traits\HasMediaUploads;
use App\Traits\PasswordReset;
use App\Traits\SalaryCalculator;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
#[ObservedBy(StaffObserver::class)]
class Staff extends Authenticatable implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasMediaUploads, HasAuditFields, HasPermissions, HasRoles, Notifiable, PasswordReset, SalaryCalculator;
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useFallbackUrl(asset('images/logo-blur.png'));
    }

    protected string $guard = 'staff';
     protected $guard_name = 'staff';

    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];



    public function cachedPermissions()
    {
        return Cache::rememberForever("staff_permissions:$this->id", function () {
            return $this->getAllPermissions()->pluck('name')->toArray();
        });
    }

    public function hasCachedPermission($permission): bool
    {
        return in_array($permission, $this->cachedPermissions());
    }

    public function clearPermissionCache(): void
    {
        Cache::forget("staff_permissions:$this->id");
    }
    protected function casts(): array
    {
        return [
            'suspended' => 'boolean',
        ];
    }


    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn() => "$this->first_name  $this->last_name"
        );
    }

    public function trashInfo(): Attribute
    {
        return new Attribute(
            get: fn() => "$this->first_name  $this->last_name | $this->staff_id"
        );
    }


    public function profile(): MorphOne
    {
        return $this->morphOne(Profile::class, 'profileable');
    }

    /**
     * Get all the classrooms the staff teaches
     * @return BelongsToMany
     */
    public function assignedClassrooms(): BelongsToMany
    {
        return $this->belongsToMany(
            Classroom::class,
            StaffClassroomSubjectPermission::class,
            'staff_id',
            'classroom_id'
        )->distinct()
            ->with('subjects')
            ->wherePivotNull('deleted_at');
    }

    /**
     * Get the subjects assigned to the staff member.
     */
    public function assignedSubjects(): BelongsToMany
    {
        return $this->belongsToMany(
            Subject::class,
            StaffClassroomSubjectPermission::class,
            'staff_id',
            'subject_id'
        )
            ->withPivot('classroom_id')
            ->distinct()
            ->wherePivotNull('deleted_at');
    }

    public function assignedSubjectsWithClassrooms()
    {
        return $this->belongsToMany(
            Subject::class,
            StaffClassroomSubjectPermission::class,
            'staff_id',
            'subject_id'
        )
            ->with(['classes' => function ($query) {
                $query->select('classrooms.id', 'classrooms.name');
            }])
            ->distinct();
    }

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class,);
    }


    protected $hidden = [
        'password',
        'remember_token',
    ];

//    public function getAuthIdentifierName(): string
//    {
//        return 'email';
//    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function replies(): MorphMany
    {
        return $this->morphMany(Reply::class, 'replyable');
    }

    public function lendBooks(): MorphMany
    {
        return $this->morphMany(LentBook::class, 'lentable');
    }
    public function attendances(): MorphMany
    {
        return $this->morphMany(Attendance::class, 'attendable');
    }

    public function allowancesAndDeductions()
    {
        return $this->belongsToMany(
            AllowanceAndDeduction::class, StaffAllowanceDeduction::class, 'staff_id', 'allowance_id')
            ->withPivot('applied_at')
            ->withTimestamps();
    }

    public function bank(): MorphOne
    {
        return $this->morphOne(Bank::class, 'bankable', 'bankable_type', 'bankable_id', 'id');
    }

    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class);
    }

    public function allowances()
    {
        return $this->allowancesAndDeductions()
            ->where('type', AllowanceType::ALLOWANCE->value);
    }

    public function deductions()
    {
        return $this->allowancesAndDeductions()
            ->where('type', AllowanceType::DEDUCTION->value);
    }

    public function scopeSearch($query, $search)
    {
        return $query
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%')
                        ->orWhere('uuid', 'like', '%' . $search . '%')
                    ;
                });
            });
    }
}
