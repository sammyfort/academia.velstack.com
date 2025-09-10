<?php

namespace App\Observers;

use App\Models\Bill;
use App\Models\Payment;
use App\Traits\ActivityLogger;
use Illuminate\Support\Facades\Cache;

class BillObserver
{
    use ActivityLogger;

    /**
     * Handle the Bill "created" event.
     */
    public function created(Bill $bill): void
    {
       $this->refreshCache($bill);
        $this->logCreated($bill, $bill->fee->name);
    }

    /**
     * Handle the Bill "updated" event.
     */
    public function updated(Bill $bill): void
    {
        $this->refreshCache($bill);
    }

    /**
     * Handle the Bill "deleted" event.
     */
    public function deleted(Bill $bill): void
    {
        $this->refreshCache($bill);
       // $bill->payments()->delete();
    }

    /**
     * Handle the Bill "restored" event.
     */
    public function restored(Bill $bill): void
    {
        $this->refreshCache($bill);
    }

    /**
     * Handle the Bill "force deleted" event.
     */
    public function forceDeleted(Bill $bill): void
    {
        //
    }

    private function refreshCache(Bill $bill): void
    {
        Cache::forget("fees:$bill->school_id");

        $feeIndex = "feeIndex:$bill->school_id";
        $debts = "feeDebt:$bill->school_id";

        Cache::increment($feeIndex);
        Cache::increment($debts);
    }
}
