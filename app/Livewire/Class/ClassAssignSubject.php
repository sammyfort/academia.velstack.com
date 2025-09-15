<?php

namespace App\Livewire\Class;

use App\Models\Classroom;
use App\Models\Subject;
use App\Services\DataTable;
use App\Traits\CacheStore;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ClassAssignSubject extends Component
{
    use WithPagination, WithoutUrlPagination, CacheStore;
    public string $search = '';

    public function attach($class, $subject): void
    {
        $classroom = school()->classes()->where('id', $class)->firstOrFail();
        $sub = school()->subjects()->where('id', $subject)->firstOrFail();
        $classroom->subjects()->attach($sub->id, [
            'uuid' => (string) Str::uuid(),
            'school_id' => $classroom->school_id,
        ]);
        $this->dispatch('success', 'Subject Assigned');

    }

    public function remove(Classroom $class, Subject $subject): void
    {
        $class->subjects()->detach($subject->id);
        $this->dispatch('success', 'Subject Removed');
    }


    public function render()
    {
        return view('livewire.class.class-assign-subject', [
            'classes' => (new DataTable(new Classroom()))
                ->query(function ($query) {
                    $query->where('school_id', school()->id);
                })
                ->with(['subjects'])
            ->searchable($this->search)
            ->paginate(15),
            'subjects' => school()->subjects()->get()
        ]);
    }
}
