<?php

namespace App\Livewire\Chart;

use App\Models\Term;
use App\Traits\CacheStore;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Carbon\Carbon;
use Livewire\Component;

class ClassAverages extends Component
{
    use CacheStore;
    public string $selectedTermName;
    public Term  $term;

    public function mount(): void
    {
        $this->term = currentTerm();
        $this->selectedTermName = $this->term->name;
    }

    public function selectTerm($termId): void
    {
        $term = school()->terms()->findOrFail($termId);
        $this->term = $term;
        $this->selectedTermName = $this->term->name;
    }


    public function classChartData(): ColumnChartModel
    {
        $data = getClassAveragesForTerm($this->term->id);
        $classAverage =   (new ColumnChartModel())
            ->setTitle('Class Averages')
            ->setAnimated(true)
            ->withoutLegend()
            ->setSmoothCurve();
        foreach ($data as $key => $value) {
            $classAverage->addColumn($key, $value, randomColor());
        }
        return $classAverage;
    }


    public function render()
    {

        return view('livewire.chart.class-averages', [
            'terms' => $this->getTerms(),
            'classChartData' => $this->classChartData(),
        ]);
    }
}
