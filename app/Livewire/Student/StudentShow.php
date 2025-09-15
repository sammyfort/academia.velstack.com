<?php

namespace App\Livewire\Student;


use App\Models\Student;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class StudentShow extends Component
{
    use WithPagination, WithoutUrlPagination;

    public Student $student;
    public string $search = '';
    public int $paginate = 10;
    public bool $show_parents = false;

    public function setDisplay(bool $value): void
    {
        $this->show_parents = $value;

    }

    public function resetFilter(): void
    {
        $this->search = '';
        $this->paginate = 10;
    }


    public function mount($uuid): void
    {
        $this->student = Student::query()
            ->where('uuid', $uuid)
            ->with(['school', 'class', 'subjects', 'payments', 'outstandingBills', 'bills'])
            ->firstOrFail();
    }

    public function studentChartData(): LineChartModel
    {
        $data = getStudentAveragesAcrossTerms($this->student->id);
        $chart = (new LineChartModel())
            ->setTitle('Performance')
            ->setAnimated(true)
            ->setSmoothCurve();
        foreach ($data as $key => $value) {
            $chart->addPoint($key, $value);
        }

        $chart->setJsonConfig([
            'colors' => ['#1ea001'],
            'markers' => [
                'size' => 5,
                'strokeWidth' => 2,
                'colors' => ['#e5cb06'],
                'hover' => [
                    'size' => 6,
                ],
            ],
        ]);

        return $chart;
    }

    #[On('recordDeleted')]
    public function render(): View
    {
        return view('livewire.student.student-show', [
            'payments' => $this->student->payments()->search($this->search)->paginate($this->paginate),
            'debts' => $this->student->outstandingBills()->search($this->search)->paginate($this->paginate),
            'bills' => $this->student->bills()->search($this->search)->paginate($this->paginate),
            'chart' => $this->studentChartData(),
        ]);
    }
}
