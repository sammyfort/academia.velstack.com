<?php

namespace App\Livewire\Class;

use App\Enum\ClassRole;
use App\Models\Staff;
use App\Models\StaffClassroomSubjectPermission;
use App\Services\DataTable;
use App\Traits\CacheStore;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ClassAssignStaff extends Component
{
    use WithPagination, WithoutUrlPagination, CacheStore;
    protected $listeners = [
        'open-assignment' => 'loadStaff'
    ];

    public Staff $assignee;

    public array|Collection $class_subjects = [];

    public string $search = "";
    public string $permissionSearch = '';
    public int $paginate = 15;

    public string $direction = "desc";

    public array $assignment = [
        'class_id' => '',
        'role_name' => '',
        'subject_id' => ''
    ];

    public function resetFilter(): void
    {
        $this->search = "";
        $this->paginate = 10;
        $this->direction = 'desc';
    }

    public function loadStaff($staff_id): void
    {
        $this->assignee = school()->staff()->findOrFail($staff_id);
    }

    public function updatedAssignmentRoleName($value): void
    {
        if ($value && $value == ClassRole::SUBJECT_TEACHER->value) {
            if ($this->assignment['class_id']) {
                $class = school()->classes()->findOrFail($this->assignment['class_id']);
                $this->class_subjects = $class->subjects()->get();
            }


        }
    }

    public function assign(): void
    {
        $this->validate([
            'assignment.class_id' => ['required', 'int', 'exists:classrooms,id'],
            'assignment.role_name' => ['required', Rule::in(ClassRole::cases())],
            'assignment.subject_id' => [Rule::requiredIf($this->assignment['role_name'] == ClassRole::SUBJECT_TEACHER->value),
               Rule::exists('subjects', 'id')],
        ]);

        $class = school()->classes()->findOrFail($this->assignment['class_id']);

        DB::beginTransaction();
        try {
            switch ($this->assignment['role_name']) {
                case ClassRole::SUBJECT_TEACHER->value:
                    $record = StaffClassroomSubjectPermission::query()
                        ->where('staff_id', $this->assignee->id)
                        ->where('classroom_id', $class->id)
                        ->where('subject_id', $this->assignment['subject_id'])
                        ->exists();
                    if ($record) {
                        $this->addError('assignment.class_id', 'This subject is already assigned to this staff in the selected class');
                        return;
                    }

                    assignClassSubjectPermission($this->assignee, $class, $this->assignment['role_name'], $this->assignment['subject_id']);
                    $this->dispatch('success', "Subject assigned to staff in $class->name");
                    DB::commit();
                    break;

                case ClassRole::CLASS_TEACHER->value:
                    $record = StaffClassroomSubjectPermission::query()
                        ->where('staff_id', $this->assignee->id)
                        ->where('classroom_id', $class->id)
                        ->whereNull('subject_id')
                        ->exists();
                    if ($record) {
                        $this->addError('assignment.class_id', "This staff is already assigned to $class->name");
                        return;
                    }

                    assignClassSubjectPermission($this->assignee, $class, $this->assignment['role_name']);
                    $this->dispatch('success', "Staff Assigned $class->name");
                    DB::commit();
                    break;

                default;
            }

        }catch (\Exception $exception){
            DB::rollBack();
            $this->dispatch('error', $exception->getMessage());
        }

    }

    public function removeSubject($staffId, $classroomId, $subjectId): void
    {
        detachSubject($staffId, $classroomId, $subjectId);
    }

    public function removeClassroom($staffId, $classroomId): void
    {
        detachClassroom($staffId, $classroomId);
    }



    public function render()
    {
        return view('livewire.class.class-assign-staff', [
            'staff' => (new DataTable(new Staff()))
                ->query(function ($query) {
                    $query->where('school_id', school()->id);
                })->searchable($this->search)
                ->latest()
                ->paginate($this->paginate),
            'classes' => $this->getClasses()
        ]);
    }
}
