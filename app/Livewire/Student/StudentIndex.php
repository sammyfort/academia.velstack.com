<?php

namespace App\Livewire\Student;

use App\Exports\Student\StudentExport;
use App\Models\Student;
use App\Services\DataTable;
use App\Traits\CacheStore;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class StudentIndex extends Component
{
    use WithPagination, WithoutUrlPagination, CacheStore;

    public string $search = '';
    public $date = '';
    public ?int $class_id = null;
    public ?int $parent_id = null;
    public int $paginate = 15;
    public bool $queryAlumni = false;



    public function resetFilter(): void
    {
        $this->search = "";
        $this->date = "";
        $this->class_id = null;
        $this->parent_id = null;
        $this->queryAlumni = false;
    }

    public function graduateStudent($id): void
    {

        school()->students()->findOrFail($id)->update(['is_completed' => true]);
        $this->dispatch('success', 'Student mark as graduate');
    }

    public function unGraduateStudent($id): void
    {
        school()->alumni()->findOrFail($id)->update(['is_completed' => false]);
        $this->dispatch('success', 'Student mark as un-graduated');
    }



   


    public function exportStudents():  BinaryFileResponse
    {
        return Excel::download(new StudentExport(), "students.xlsx");
    }


    #[On('recordDeleted')]
    public function render()
    {
        $school = school();

        return view('livewire.student.student-index', [
            'students' => (new DataTable(new Student()))
                ->searchable($this->search)
                ->with(['class'])
                ->query(function ($query) use ($school) {
                    $query->where('school_id', $school->id);
                    if (!empty($this->class_id)) {
                        $query->where('class_id', $this->class_id);
                    }
                    if (!empty($this->parent_id)) {
                        $query->whereHas('parents', function ($parentQuery) {
                            $parentQuery->where('id', $this->parent_id);
                        });
                    }
                    $query->where('is_completed', $this->queryAlumni);
                })->paginate($this->paginate),
            'classes' => $this->getClasses(),
            'parents' => $this->getParents(),
            'alumni' => school()->allStudents()->completed()->get(),
        ]);
    }
}
