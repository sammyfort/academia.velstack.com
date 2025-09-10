<?php

namespace App\Observers;

use App\Models\Fee;
use App\Models\Payment;
use App\Traits\ActivityLogger;
use Illuminate\Support\Facades\Cache;

class PaymentObserver
{
    use ActivityLogger;

    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        $this->refreshCache($payment);
        $this->logCreated($payment, "of GHS $payment->amount", 'finance.payment.receipt');
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        $this->refreshCache($payment);
        $this->logUpdated($payment, $payment->amount, 'finance.payment.receipt');
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        $this->refreshCache($payment);
        $this->logDeleted($payment, $payment->amount);
    }

    /**
     * Handle the Payment "restored" event.
     */
    public function restored(Payment $payment): void
    {
        $this->refreshCache($payment);
        $this->logRestored($payment, $payment->amount, 'finance.payment.receipt');
    }

    /**
     * Handle the Payment "force deleted" event.
     */
    public function forceDeleted(Payment $payment): void
    {
        $this->refreshCache($payment);
        $this->logDeleted($payment, $payment->amount);
    }

    private function refreshCache(Payment $payment): void
    {
        Cache::forget("fees:$payment->school_id");

        $feeIndex = "feeIndex:$payment->school_id";
        $paymentHistory = "paymentHistory:$payment->school_id";
        $debts = "feeDebt:$payment->school_id";

        Cache::increment($feeIndex);
        Cache::increment($paymentHistory);
        Cache::increment($debts);
    }



}
