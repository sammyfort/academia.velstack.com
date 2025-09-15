<?php

namespace App\Livewire\Class;

use App\Enum\ClassRole;
use App\Enum\Remark\Type;
use App\Exports\Student\StudentExport;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Term;
use App\Services\ClassService;
use App\Services\DataTable;
use App\Traits\CacheStore;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ClassShow extends Component
{
    use WithPagination, WithoutUrlPagination, CacheStore;

    protected $listeners = [
        'enter-result' => 'handleOpenResult',
        'close-result' => 'closedResultsModal',
        'open-remark' => 'openRemarkModal',
    ];
    public Classroom $class;
    public string $search = '';
    public int $paginate = 15;
    public Date|string $date;
    public ?int $term_id = null;
    public Term $term;

    // ACADEMIC REPORT
    public array|Collection $selectedStudents = [];
    public bool $allSelected = false;

    public array $promotion = [
        'class_id' => '',
    ];

    public array|Collection $studentSubjects = [];
    public Student $studentForScore;
    public ?int $subject_id = null;
    public $dynamicScores = [];
    public $scoreTypes = [];
    // END ACADEMIC REPORT

    // ATTENDANCE
    public ?int  $attendanceSheetStudentId = null;
    public ?Student $studentModel;
    public array|Collection $attendances = [];
    public bool $showTimeSheet = false;
    // END ATTENDANCE


    // SCORE TYPE
    public array $assignScoreType = [
        'subject_id' => '',
        'score_type_id' => '',
    ];
    // END SCORE TYPE

    // REMARK
    public Student $studentForRemark;

    public array $remarkOption = [
        'type' => '',
        'interest' => '',
        'attitude' => '',
        'conduct' => '',
        'remark' => ''
    ];



    public function openRemarkModal($id): void
    {
        $this->studentForRemark = $this->class->students()->findOrFail($id);
    }

    public function updatedRemarkType($value): void
    {
      //
    }

    public function addRemark(): void
    {

        $this->validate([
           'remarkOption.type' => ['required'],
            'remarkOption.interest' => [Rule::requiredIf($this->remarkOption['type'] == 'form-master')],
            'remarkOption.attitude' => [Rule::requiredIf($this->remarkOption['type'] == 'form-master')],
            'remarkOption.conduct' => [Rule::requiredIf($this->remarkOption['type'] == 'head-master')],
            'remarkOption.remark' => [Rule::requiredIf($this->remarkOption['type'] == 'head-master')],
        ]);

        $termId = $this->term->id;
        $studentId = $this->studentForRemark->id;

        // Attempt to find an existing remark for the student in the specified term
        $existingRemark = $this->class->reportRemarks()->where('term_id', $termId)
            ->where('student_id', $studentId)
            ->first();

        if ($existingRemark) {
            // Update the existing remark
            $existingRemark->update([
                'interest' => $this->remarkOption['interest'] ?? null,
                'attitude' => $this->remarkOption['attitude'] ?? null,
                'conduct' => $this->remarkOption['conduct'] ?? null,
                'remark' => $this->remarkOption['remark'] ?? null,
            ]);
            $this->dispatch('success', 'Remark updated.');
        } else {
            // Create a new remark
            $this->class->reportRemarks()->create([
                'student_id' => $studentId,
                'school_id' => $this->class->school_id,
                'term_id' => $termId,
                'interest' => $this->remarkOption['interest'] ?? null,
                'attitude' => $this->remarkOption['attitude'] ?? null,
                'conduct' => $this->remarkOption['conduct'] ?? null,
                'remark' => $this->remarkOption['remark'] ?? null,
            ]);

            $this->dispatch('success', 'Remark added.');
        }

    }

    /**
     * @throws Exception
     */
    public function mount($uuid): void
    {
        $this->class = school()->classes()->where('uuid', $uuid)
            ->with(['students', 'subjects', 'scoreTypes', 'subjects.scoreTypes', 'subjects.students'])
            ->firstOrFail();

        $this->date = now()->format('Y-m-d');
        $this->term_id = currentTerm()->id;
        $this->term = currentTerm();


    }

    public function updatedTermId($value): void
    {
        if ($value) {
            $this->term = school()->terms()->findOrFail($value);
        }
        if ($this->attendanceSheetStudentId) {
            $this->setTimeSheet();
        }
    }


    public function resetFilter(): void
    {
        $this->search = "";
        $this->paginate = 10;
    }

    public function handleOpenResult($id): void
    {
        $this->studentForScore = $this->class->students()->findOrFail($id);
        $this->studentSubjects = $this->studentForScore->subjects()->get()->toArray();
    }

    public function closedResultsModal(): void
    {
        $this->resetDynamicValues();
        $this->subject_id = null;
    }

    public function resetDynamicValues(): void
    {
        $this->dynamicScores = [];
        $this->scoreTypes = [];
    }

    public function updatedSubjectId($value): void
    {
        $this->resetDynamicValues();
        $subject = $this->studentForScore->subjects->findOrFail($value);
        if (!hasClassPermission(staff(), $this->class, ClassRole::SUBJECT_TEACHER->value, $subject->id)) {
            $this->dispatch('error', "Please you do not have the permission to enter scores for $subject->name");
            return;
        }


        if ($subject) {
            $this->scoreTypes = $subject->scoreTypes->where('class_id', $this->class->id);
            $existingScores = $this->studentForScore->scores()
                ->where('subject_id', $value)
                ->where('term_id', $this->term_id)
                ->get();

            foreach ($this->scoreTypes as $score) {
                $this->dynamicScores[$score->id] = $existingScores->where('score_type_id', $score->id)->first()->score ?? '';
            }
        } else {
            $this->scoreTypes = [];
            $this->dynamicScores = [];
        }
    }

    public function updatedAllSelected($value): void
    {
        if ($value) {
            $this->selectedStudents = $this->class->students()->pluck('id')->toArray();
        } else {
            $this->selectedStudents = [];
        }
    }

    public function updatedSelectedStudents(): void
    {
        $this->allSelected = count($this->selectedStudents) === $this->class->students()->count();
    }

    public function promoteStudents(): void
    {
        $this->validate([
            'promotion.class_id' => ['required', 'exists:classrooms,id']
        ]);
        $class = school()->classes()->findOrFail($this->promotion['class_id']);

        if ($this->class->students()->get()->isNotEmpty()) {
            if (collect($this->selectedStudents)->isNotEmpty()) {
                $students = $this->class->students()->whereIn('id', $this->selectedStudents)->get();
                DB::beginTransaction();
                try {
                    foreach ($students as $student) {
                        $student->update(['class_id' => $this->promotion['class_id']]);
                    }
                    DB::commit();
                    $this->dispatch('success', "Students promoted to $class->name!");
                    $this->selectedStudents = [];
                    $this->allSelected = false;
                    cacheForget("classIndex:{$this->class->school_id}");

                } catch (Exception $exception) {
                    DB::rollBack();
                    $this->dispatch('error', $exception->getMessage());
                }
            } else {
                $this->dispatch('error', "No student is selected!");
            }

        } else {
            $this->dispatch('error', "No student found!");
        }
    }

    public function markCompleted(): void
    {
        if ($this->class->students()->get()->isNotEmpty()) {
            if (collect($this->selectedStudents)->isNotEmpty()) {
                $students = $this->class->students()->whereIn('id', $this->selectedStudents)->get();
                DB::beginTransaction();
                try {
                    foreach ($students as $student) {
                        $student->update(['is_completed' => true]);
                    }
                    DB::commit();
                    $this->dispatch('success', "Students mark as graduates!");
                    $this->selectedStudents = [];
                    $this->allSelected = false;
                    cacheForget("classIndex:{$this->class->school_id}");

                } catch (Exception $exception) {
                    DB::rollBack();
                    $this->dispatch('error', $exception->getMessage());
                }
            } else {
                $this->dispatch('error', "No student is selected!");
            }

        } else {
            $this->dispatch('error', "No student found!");
        }
    }

    public function saveScore(): void
    {
        $this->validate([
            'subject_id' => 'required|exists:subjects,id',
            'dynamicScores' => 'array',
            'dynamicScores.*' => 'required|numeric'
        ]);


        if ($this->term->hasEnded()) {
            $this->dispatch('error', "This academic term has ended and you cannot enter or edit scores");
            return;
        }

        DB::beginTransaction();
        try {
            foreach ($this->scoreTypes as $scoreType) {
                $scoreValue = $this->dynamicScores[$scoreType->id] ?? null;
                if ($scoreValue === null) {
                    continue;
                }

                if ($scoreValue > $scoreType->percentage) {
                    $this->addError("dynamicScores.$scoreType->id",
                        "$scoreType->name cannot exceed its maximum percentage ($scoreType->percentage%).");
                    return;
                }

                $score = $this->studentForScore->scores()
                    ->where('subject_id', $this->subject_id)
                    ->where('term_id', $this->term->id)
                    ->where('score_type_id', $scoreType->id)
                    ->where('class_id', $this->class->id)
                    ->first();
                if ($score) {
                    $score->update(['score' => $scoreValue]);

                } else {
                    $this->studentForScore->scores()->create([
                        'school_id' => $this->studentForScore->school_id,
                        'class_id' => $this->class->id,
                        'term_id' => $this->term->id,
                        'subject_id' => $this->subject_id,
                        'score_type_id' => $scoreType->id,
                        'score' => $scoreValue,
                    ]);
                }
            }
            DB::commit();
            $this->dispatch('success', "Scores saved!");
        } catch (Exception $exception) {
            DB::rollBack();
            $this->dispatch('error', $exception->getMessage());
        }
    }

    public function recordAttendance(int $student_id, $present = true): void
    {
        $student = $this->class->students()->findOrFail($student_id);
        $term = $student->school->terms()->findOrFail($this->term_id);
        recordAttendance($student, $this->date, $term, $present);
        $this->dispatch('success', __('Attendance record!'));
    }


    public function openTimeSheet($student_id): void
    {
        $this->attendanceSheetStudentId = $student_id;
        $this->setTimeSheet();
    }

    public function setTimeSheet(): void
    {


        $this->studentModel = $this->class->students()->findOrFail($this->attendanceSheetStudentId);
        $this->attendances = $this->studentModel->attendances()
            ->where('term_id', $this->term_id)
            ->get();
        $this->showTimeSheet = true;

    }

    public function closeTimeSheet(): void
    {
        $this->studentModel = null;
        $this->attendances = [];
        $this->showTimeSheet = false;
        $this->attendanceSheetStudentId = null;
    }

    public function removeScoreType($subject_id, $score_id): void
    {
        $subject = $this->class->subjects()->findOrFail($subject_id);
        $subject->scoreTypes()->detach($score_id);
        $this->dispatch('success', 'Score Removed');
    }

    public function attachScoreToSubject(): void
    {
        $this->validate([
            'assignScoreType.subject_id' => ['required', 'int', 'exists:subjects,id'],
            'assignScoreType.score_type_id' => ['required', 'string', 'exists:score_types,id'],
        ]);

        $subject = $this->class->subjects()->findOrFail($this->assignScoreType['subject_id']);
        $score_type = $this->class->scoreTypes()->findOrFail($this->assignScoreType['score_type_id']);

        if ($subject->scoreTypes()->where('score_type_id', $score_type->id)->exists()) {
            $this->addError('assignScoreType.score_type_id', 'This score type already exists for the selected subject.');
            return;
        }

        $subject->scoreTypes()->attach($score_type, [
            'school_id' => $this->class->school_id,
            'class_id' => $this->class->id,
            'uuid' => Str::uuid(),
            'term_id' => $this->term_id
        ]);
        $this->dispatch('success', __('Subject added!'));
    }

    public function syncSubjects(): void
    {
        DB::beginTransaction();
        try {
            $students = $this->class->students;
            $subjects = $this->class->subjects;
            foreach ($students as $student) {
                foreach ($subjects as $subject) {
                    if (!in_array($subject->id, $student->subjects->pluck('id')->toArray())) {
                        $student->subjects()->attach($subject->id, [
                            'uuid' => Str::uuid(),
                            'school_id' => $this->class->school_id,
                            'class_id' => $this->class->id
                        ]);
                    }
                }
            }
            DB::commit();
            $this->dispatch('success', 'Successfully synced subjects');
            //  $this->dispatch('updateComponent');
        } catch (Exception $exception) {
            DB::rollBack();
            //throw  $exception;
            $this->dispatch('error', $exception->getMessage());
        }
    }


    public function exportStudents(): BinaryFileResponse
    {
        return Excel::download(new StudentExport($this->class->id), "{$this->class->name}-students.xlsx");
    }


    #[On(['recordDeleted'])]
    public function render()
    {

        return view('livewire.class.class-show', [
            'students' =>
                (new DataTable(new Student()))
                    ->query(function ($query) {
                        $query->where('school_id', school()->id);
                        $query->where('class_id', $this->class->id);
                        $query->notCompleted();
                    })
                    ->with(['subjects', 'attendances'])
                    ->searchable($this->search)
                    ->paginate($this->paginate),
            'rankings' => $this->class->getClassRankingForTerm($this->term_id, $this->search),
            'terms' => $this->getTerms(),
            'classes' => school()->classes()->get()->except($this->class->id),
        ]);
    }
}
