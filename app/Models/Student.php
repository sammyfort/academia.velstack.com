<?php

namespace App\Models;

use App\Enums\Gender;
use App\Observers\StudentObserver;
use App\Traits\HasAuditFields;
use App\Traits\HasMediaUploads;
use App\Traits\TerminalReport;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[ObservedBy(StudentObserver::class)]
class Student extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasMediaUploads, HasAuditFields, SoftDeletes, TerminalReport;
    protected string $guard = 'student';
    protected $guarded = ['id', 'uuid', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useFallbackUrl(asset('images/logo-blur.png'));
    }

    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn() => "$this->first_name $this->middle_name $this->last_name"
        );
    }

    public function trashInfo(): Attribute
    {
        return new Attribute(
            get: fn() => "$this->fullname - {$this->class->name} - $this->index_number",
        );
    }

    public function currentTermRemark(): HasOne
    {
        return $this->hasOne(ReportRemark::class);
    }

    public function scopeNotCompleted($query)
    {

        return $query->where('is_completed', '=', false);
    }

    public function scopeCompleted($query)
    {

        return $query->where('is_completed', '=', true);
    }


    public function reportRemarks(): HasMany
    {
        return $this->hasMany(ReportRemark::class);
    }

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'is_completed' => 'boolean',
            'suspended' => 'boolean',
        ];
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(
            _Parent::class, ParentStudent::class,
            'student_id', 'parent_id')
            ->withTimestamps();
    }


    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }



    public function class(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id', 'id');
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'student_subject')
            ->using(StudentSubject::class)
            ->withPivot(['class_id', 'term_id', 'created_by', 'school_id'])
            ->wherePivot('class_id', function ($query) {
                $query->select('class_id')
                    ->from('students')
                    ->whereColumn('students.id', 'student_subject.student_id');
            })
            ->wherePivot('school_id', function ($query) {
                $query->select('school_id')
                    ->from('students')
                    ->whereColumn('students.id', 'student_subject.student_id');
            })
            ->withTimestamps();
    }




    public function hasSubject($subject_id): bool
    {
        return $this->subjects()->where('subject_id', $subject_id)->exists();
    }


    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    public function academicRecords(): HasMany
    {
        return $this->hasMany(Score::class)
            ->whereRaw('id IN (
            SELECT MIN(id)
            FROM scores
            WHERE student_id = ?
            GROUP BY class_id, term_id
        )', [$this->id])
            ->orderBy('class_id')
            ->orderBy('term_id');
    }



    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class);
    }
    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class, 'student_id', 'id')
            ->whereHas('fee');
    }

    public function hasFees($fee_id, $term_id): bool
    {
        return $this->bills()->where('fee_id', $fee_id)
            ->where('term_id', $term_id)->exists();
    }


    /**
     * Get all the outstanding bills of the student
     * @param null $fee_id
     * @return HasMany
     */
    public function outstandingBills($fee_id = null): HasMany
    {
        return $this->hasMany(Bill::class)->where(function ($query) {
            $query->doesntHave('payments')
                ->orWhereRaw('amount > (SELECT SUM(payment_bills.amount_paid) FROM payment_bills WHERE payment_bills.bill_id = bills.id)');
        })
            ->whereHas('fee')
            ->when($fee_id, function ($query) use ($fee_id) {
                $query->whereHas('fee', function ($query) use ($fee_id) {
                    $query->where('id', $fee_id);
                });
            });
    }

    /**
     * Get all the outstanding bills of the student
     * @return int|float
     */
    public function totalDebt(): int|float
    {
        return $this->outstandingBills->sum(function ($bill) {
            $paidAmount = $bill->payments()->sum('amount_paid');
            return number_format($bill->amount - $paidAmount, 2);
        });
    }


    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class)
            ->whereHas('school', function ($query) {
            $query->where('schools.id', $this->school_id);
        });
    }

    public function surplusPayments(): HasMany
    {
        return $this->hasMany(SurplusPayment::class)->where('amount', '>', 0);
    }

    public function totalSurplus(): float
    {
        return $this->surplusPayments()->sum('amount');
    }

    public function scopeWithOverflows($query)
    {
        return $query->whereHas('surplusPayments', function ($q){
             $q->where('amount', '>', 0);
        });
    }


    public function profile(): MorphOne
    {
        return $this->morphOne(Profile::class, 'profileable');
    }


    public function hasOutstandingDebt(): bool
    {
        return $this->bills->some(function ($bill) {
            return $bill->balance() > 0;
        });
    }

    public function getAuthIdentifierName(): string
    {
        return 'index_number';
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function scopeFilter($query, $data)
    {
        return $query->when(isset($data['index_number']), function ($query) use ($data) {
            return $query->where('index_number', $data['index_number']);
        })
            ->when(isset($data['first_name']), function ($query) use ($data) {
                return $query->whereRaw('LOWER(first_name) = ?', [strtolower($data['first_name'])]);
            })
            ->when(isset($data['last_name']), function ($query) use ($data) {
                return $query->whereRaw('LOWER(last_name) = ?', [strtolower($data['last_name'])]);
            })
            ->when(isset($data['dob']), function ($query) use ($data) {
                return $query->whereHas('profile', function ($query) use ($data) {
                    $query->where('dob', $data['dob']);
                });
            })
            ->when(isset($data['gender']), function ($query) use ($data) {
                return $query->whereHas('profile', function ($query) use ($data) {
                    $query->whereRaw('LOWER(gender) = ?', [strtolower($data['gender'])]);
                });
            })
            ->when(isset($data['mother_name']), function ($query) use ($data) {
                return $query->whereHas('parent', function ($query) use ($data) {
                    $query->whereRaw('LOWER(mother_name) = ?', [strtolower($data['mother_name'])]);
                });
            });
    }

    public function scopeSearch($query, $classId = null, $studentId = null, $search = null)
    {

        return $query
            ->when($classId, fn($q) => $q->where('class_id', $classId))
            ->when($studentId, fn($q) => $q->where('id', $studentId))
            ->when(!empty($search), function ($q) use ($search) {
                $q->where(function ($subQuery) use ($search) {
                    foreach ($this->getSearchableFields() as $field) {
                        $subQuery->orWhere($field, 'like', "%$search%");
                    }
                });
            });
    }
    public function attendances(): MorphMany
    {
        return $this->morphMany(Attendance::class, 'attendable');
    }



    public function transportation(): BelongsTo
    {
        return $this->belongsTo(Transportation::class, 'transportation_id', 'id');
    }

    public function lendBooks(): MorphMany
    {
        return $this->morphMany(LentBook::class, 'lentable');
    }

    public function transfers(): MorphMany
    {
        return $this->morphMany(Transfer::class, 'transferable');
    }

    public function overallPerformanceRate(): float
    {
        $studentScores = $this->scores()
            ->with(['subject', 'term'])
            ->get();
        $totalScored = $studentScores->sum('score');
        $totalPossibleMarks = $studentScores->count() * 100;
        return $totalPossibleMarks > 0 ? round(($totalScored / $totalPossibleMarks) * 100, 2) : 0.0;
    }

    public function classPerformanceRate(): float
    {
        $studentScores = $this->scores()
            ->with(['subject', 'term'])
            ->where('class_id', $this->class_id)
            ->get();

        $totalScored = $studentScores->sum('score');
        $totalPossibleMarks = $studentScores->count() * 100;
        return $totalPossibleMarks > 0 ? round(($totalScored / $totalPossibleMarks) * 100, 2) : 0.0;
    }

    public function getSubjectPercentages(): array
    {
        // Get all subjects for the student in the current class
        $subjects = $this->subjects()->get();

        $subjectPercentages = [];

        foreach ($subjects as $subject) {
            // Fetch all scores for the student in the subject
            $scores = $this->scores()
                ->where('class_id', $this->class_id)
                ->where('subject_id', $subject->id)
                ->get(['score', 'score_type_id']);

            // Retrieve max scores for score types if applicable
            $maxScores = $this->class->scoreTypes()
                ->whereIn('id', $scores->pluck('score_type_id'))
                ->pluck('percentage', 'id'); // Assuming score_types table has `max_score`

            // Calculate total scored and total possible for the subject
            $totalScored = $scores->sum('score');
            $totalPossible = $scores->sum(function ($score) use ($maxScores) {
                return $maxScores[$score->score_type_id] ?? 0; // Use max score from score types
            });

            // Calculate percentage and store it
            $percentage = $totalPossible > 0
                ? round(($totalScored / $totalPossible) * 100, 2)
                : 0.0;

            $subjectPercentages[] = [
                'subject' => $subject->name,
                'percentage' => $percentage,
            ];
        }

        return $subjectPercentages;
    }

    public function deductSurplus(float $amount, string $description): void
    {
        $remainingAmount = $amount;

        // Get surplus records in the order they were created
        $surplusPayments = $this->surplusPayments()->where('amount', '>', 0)->orderBy('created_at')->get();

        foreach ($surplusPayments as $surplus) {
            if ($remainingAmount <= 0) {
                break;
            }

            if ($surplus->amount <= $remainingAmount) {
                // Fully consume this surplus
                $remainingAmount -= $surplus->amount;

                // Update surplus record to zero
                $surplus->update([
                    'amount' => 0,
                    'description' => "{$description} (Fully used surplus)"
                ]);
            } else {
                // Partially consume this surplus
                $surplus->update([
                    'amount' => $surplus->amount - $remainingAmount,
                    'description' => "{$description} (Partially used surplus)"
                ]);
                $remainingAmount = 0;
            }
        }

        if ($remainingAmount > 0) {
            throw new \Exception('Not enough surplus available to deduct the specified amount.');
        }
    }
}
