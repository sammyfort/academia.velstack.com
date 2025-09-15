<?php

namespace App\Livewire\Chart;

use App\Models\Term;
use App\Traits\CacheStore;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Livewire\Component;

class LevelsAverage extends Component
{
    use  CacheStore;
    public array $classAveragesByType = [];
    public string $selectedTermName = '';
    public Term  $term;

    public function mount(): void
    {
        $this->term = currentTerm();
        $this->selectedTermName = $this->term->name;
    }

    public function selectTerm($termId): void
    {
        $this->term = school()->terms()->findOrFail($termId);
        $this->selectedTermName = $this->term->name;

    }

    public function levelChartData(): PieChartModel
    {
        $data = getClassAveragesByTypeForTerm($this->term->id);
        $classAverage =   (new PieChartModel())
            ->setTitle('Level Averages')
              ->asPie();

        foreach ($data as $key => $value) {
            $classAverage->addSlice($key, $value, randomColor());
        }
        return $classAverage;
    }

    public function render()
    {
       // dd(getClassAveragesByTypeForTerm($this->term->id));
        return view('livewire.chart.levels-average', [
            'terms' => $this->getTerms(),
            'pieChartModel' => $this->levelChartData(),
        ]);
    }
}
