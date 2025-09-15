<?php

namespace App\Livewire\Fee;

use App\Models\Fee;
use App\Models\Student;
use App\Services\DataTable;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class FeeIndex extends Component
{
    use WithPagination, WithoutUrlPagination;
    public string $search = '';
    public int $paginate = 15;

    public function resetFilter(): void
    {
        $this->search = '';
        $this->paginate = 15;
    }

    public function loadData(): \Illuminate\Pagination\LengthAwarePaginator
    {
        $schoolId = school()->id;

        $versionKey = "feeIndex:$schoolId";
        $version = Cache::remember($versionKey, now()->addHours(12), function () {
            return 1;
        });
        $cacheKey = "fees_pagination:$schoolId:" . $version . ":" . md5(json_encode([
                'search'      => $this->search,
                'page'        =>  $this->getPage(),

            ]));
        return Cache::remember($cacheKey, now()->addHours(12), function () use ($schoolId) {
            return (new DataTable(new Fee()))
                ->searchable($this->search)
                ->query(function ($query) use ($schoolId) {
                    $query->where('school_id', $schoolId);
                })
                ->withCount('studentsWithDebtsRelation')
                ->withSum('paymentBills', 'amount_paid')
                ->withSum('bills', 'amount')
                ->with(['term'])
                ->latest()
                ->paginate($this->paginate);
        });
    }


    #[On('recordDeleted')]
    public function render()
    {
       // dd($this->loadData());
        return view('livewire.fee.fee-index', [
            'fees' => $this->loadData()
        ]);
    }
}
