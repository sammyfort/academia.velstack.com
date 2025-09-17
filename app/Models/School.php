<?php

namespace App\Models;

use App\Jobs\SMSSenderJob;
use App\Observers\SchoolObserver;
use App\Traits\HasAuditFields;
use App\Traits\HasMediaUploads;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HigherOrderWhenProxy;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[ObservedBy(SchoolObserver::class)]
class School extends Model implements HasMedia
{
    use HasAuditFields, HasMediaUploads, HasFactory, InteractsWithMedia, SoftDeletes;

    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('cover')
            ->singleFile();

        $this
            ->addMediaCollection('favicon')
            ->singleFile();
    }

    protected function casts(): array
    {
        return [
            'suspended' => 'boolean',
        ];
    }
    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    public function profile(): MorphOne
    {
        return $this->morphOne(Profile::class, 'profileable');
    }

    public function reportRemarks(): HasMany
    {
        return $this->hasMany(ReportRemark::class);
    }

    public function semesters(): HasMany
    {
        return $this->hasMany(Term::class);
    }

     public function allStudents(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class)->where('is_completed', '=', false);
    }

    public function alumni(): HasMany
    {
        return $this->hasMany(Student::class)->where('is_completed', '=', true);
    }

    public function parents(): HasMany
    {
        return $this->hasMany(_Parent::class);
    }

    public function classes(): HasMany
    {
        return $this->hasMany(Classroom::class, 'school_id');
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class);
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function paymentBills(): HasMany
    {
        return $this->hasMany(PaymentBill::class);
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }

    public function subscriptionIsPast(): Attribute
    {
        return new Attribute(
            get: fn () => $this->subscription->expires_at->isPast()
        );

    }

    public function communication(): HasOne
    {
        return $this->hasOne(Communication::class);
    }

    public function readyForSMS(): bool
    {
        return $this->communication->sender_id != null && $this->communication->api_key != null;
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function currentTerm()
    {
        return $this->terms()->where('status', 'active')->first();
    }

    public function authorize(): Response
    {
        return Gate::authorize('premium', $this);
    }

    public function coverImage(): string
    {
         if ($media = $this->getFirstMediaUrl('cover')) {
            return $media;
        }
        return '';
    }

    public function gradeScores(): HasMany
    {
        return $this->hasMany(GradeCode::class);

    }

    public function transportations(): HasMany
    {
        return $this->hasMany(Transportation::class);
    }

    public function favicon(): string
    {
        if ($media = $this->getFirstMediaUrl('favicon')) {
            return $media;
        }

        return '';
    }

    public function broadCastSMS($recipients, $message)
    {
        if ($this->readyForSMS()) {
            dispatch(new SMSSenderJob(
                $message,
                $recipients,
                $this->communication->sender_id,
                $this->communication->api_key,
                'School Notification',
                $this
            ));

        }

    }

    /**
     * Get all students owing any fees for this school, optionally filtered by class.
     *
     * @param  null  $classId
     * @param  null  $studentId
     * @param  null  $fee_id
     * @param  null  $search
     */
    public function studentsOwing($classId = null, $studentId = null, $fee_id = null, $search = null): Builder|HasMany|HigherOrderWhenProxy
    {
        return $this->students()
            ->search($classId, $studentId, $search)
            ->whereHas('bills', function ($query) use ($fee_id) {
                $query->where(function ($query) {
                    $query->whereRaw('(SELECT COALESCE(SUM(amount_paid), 0) FROM payment_bills WHERE payment_bills.bill_id = bills.id) < bills.amount');
                })
                    ->when($fee_id, function ($query) use ($fee_id) {
                        $query->where('bills.fee_id', $fee_id);
                    });
            })
            ->with([
                'bills' => function ($query) use ($fee_id) {
                    $query->with(['fee', 'term'])
                        ->withSum('payments', 'amount_paid')
                        ->whereRaw('(SELECT COALESCE(SUM(amount_paid), 0) FROM payment_bills WHERE payment_bills.bill_id = bills.id) < bills.amount')
                        ->when($fee_id, function ($query) use ($fee_id) {
                            $query->where('bills.fee_id', $fee_id);
                        });
                },
                'class',       // Loads the student's class (consider renaming if necessary)
                'bills.term',   // Loads the term for each bill
            ]);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'school_id', 'id');
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'school_id', 'id');
    }

    public function lendBooks(): HasMany
    {
        return $this->hasMany(LentBook::class, 'school_id', 'id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);

    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);

    }

    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class);

    }

    public function allowancesAndDeductions(): HasMany
    {
        return $this->hasMany(AllowanceAndDeduction::class);

    }

    public function preference(): HasOne
    {
        return $this->hasOne(Preference::class);
    }

    public function incomingTransfers(): HasMany
    {
        return $this->hasMany(Transfer::class, 'to_school_id')
            ->orderBy('initiated_at', 'desc');
    }

    public function outgoingTransfers(): HasMany
    {
        return $this->hasMany(Transfer::class, 'from_school_id')
            ->orderBy('initiated_at', 'desc');
    }

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class);
    }
}
