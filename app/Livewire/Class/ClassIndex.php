<?php

namespace App\Livewire\Class;

use App\Models\Classroom;
use App\Models\Fee;
use App\Models\School;
use App\Services\DataTable;
use Exception;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;


#[Layout('layouts.app')]
class ClassIndex extends Component
{

    use WithPagination, WithoutUrlPagination;
    protected $listeners = ['attached', 'class_deleted'];

    public School $school;
    public bool $toggle;
    public string $search = "";
    public int $paginate = 15;
    public string $direction = 'desc';

    public function resetFilter(): void
    {
        $this->search = "";
        $this->paginate = 15;
        $this->direction = 'desc';
    }

    public function mount(): void
    {

        $this->school = auth()->guard('staff')->user()->school;

    }

    /**
     * @throws Exception
     */
    public function openReporting(): void
    {
        currentTerm()->update(['reporting' => true]);
        $this->toggle = (bool) currentTerm()->reporting;

    }

    /**
     * @throws Exception
     */
    public function closeReporting(): void
    {
        currentTerm()->update(['reporting' => false]);
        $this->toggle = (bool) currentTerm()->reporting;

    }



    #[On('recordDeleted')]
    public function render()
    {
        return view('livewire.class.class-index', [
                'classes' => (new DataTable(new Classroom()))
                    ->searchable($this->search)
                    ->query(function ($query) {
                        $query->where('school_id', school()->id);
                    })
                    ->withCount('students')
                    ->latest()
                    ->paginate($this->paginate),
            ]
        );

    }
}
