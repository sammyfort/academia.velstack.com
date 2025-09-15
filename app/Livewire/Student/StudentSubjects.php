<?php

namespace App\Livewire\Student;

use App\Enum\ClassRole;

use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\Subject;

use App\Traits\ActivityLogger;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;


class StudentSubjects extends Component
{

    use ActivityLogger;

    public Student $student;

    public function mount($uuid): void
    {
        $this->student = school()->students()
            ->where('uuid', $uuid)
            ->with(['class', 'class.subjects', 'subjects'])
            ->withCount('subjects')
            ->firstOrFail();
        permittedTo($this->student->class, ClassRole::CLASS_TEACHER->value, NULL, true);

    }

    public function attach($sub): void
    {

        DB::beginTransaction();
        try {
            $this->student->subjects()->attach($sub, [
                'uuid' => (string) Str::uuid(),
                'school_id' => $this->student->school_id,
                'class_id' => $this->student->class_id
            ]);
            $pivotData = StudentSubject::query()
                ->where('student_id', $this->student->id)
                ->where('class_id', $this->student->class_id)
                ->where('subject_id', $sub)
                ->first();

            $user = auth()->user();
            $this->logCreated($pivotData,
                $pivotData->classroom->name,
                'classes.assign.staff',
                'View Assignment',
                "$user->fullname attached new subjects ({$pivotData->subject->name}) to student
             {$pivotData->student->fullname} in {$pivotData->classroom->name}"
            );

            $this->dispatch('success', 'Subject Assigned');
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            \Illuminate\Log\log()->error($exception);
            $this->dispatch('error', $exception->getMessage());
        }




    }

    public function remove(Subject $subject): void
    {

        DB::beginTransaction();
        try {
            $pivotData = StudentSubject::query()
                ->where('student_id', $this->student->id)
                ->where('class_id', $this->student->class_id)
                ->where('subject_id', $subject->id)
                ->first();

            $this->student->subjects()->detach($subject->id);

            $user = auth()->user();
            $this->logDeleted($pivotData,
                $pivotData->classroom->name,
                "$user->fullname removed subject ({$pivotData->subject->name}) from student
             {$pivotData->student->fullname} in {$pivotData->classroom->name}"
            );

            $this->dispatch('success', 'Subject removed');
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            \Illuminate\Log\log()->error($exception);
            $this->dispatch('error', $exception->getMessage());
        }
    }


    public function render()
    {
        return view('livewire.student.student-subjects', [

        ]);
    }
}
