<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\PaymentBill;

class PaymentBillObserver
{
    public function deleting(PaymentBill $bill): void
    {
        $payment = $bill->payment;

        if ($payment && $payment->bills()->count() <= 1) {
            $payment->delete();
        }
    }

    public function created(PaymentBill $bill): void
    {
        $this->updateBill($bill);

    }

    public function updated(PaymentBill $bill): void
    {
        $this->updateBill($bill);

    }

    public function updateBill(PaymentBill $payment): void
    {

        $totalPaid = $payment->bill->payments()->sum('amount_paid');

        $payment->bill()->update([
            'amount_paid' => $totalPaid,
        ]);

    }
}
