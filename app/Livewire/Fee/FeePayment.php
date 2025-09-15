<?php

namespace App\Livewire\Fee;

use App\Enum\PaymentChannel;
use App\Models\Student;
use App\Traits\CacheStore;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use Livewire\Component;


class FeePayment extends Component
{
    use CacheStore;


    public array $payment = [
        'created_at' => '',
        'student_id' => '',
        'channel' => PaymentChannel::MANUAL->value,
        'amount' => '',
        'payer_name' => '',
        'payer_phone' => '',
    ];



    public ?Student $student;
    public  $printOut = null;

    public array|Collection $bills = [];
    public $totalDebt = 0;


    public function selectStudent($id): void
    {

       if ($id){
           $this->payment['student_id'] = $id;
           $this->student = school()->students()->findOrFail($this->payment['student_id']);
           $this->totalDebt = $this->student->totalDebt();
           $this->bills = $this->student->bills()->outstanding()->get();
           $this->payment['amount'] = array_fill(0, $this->bills->count(), 0);
       }else {
           $this->payment['student_id'] = '';
           $this->student = null;
           $this->totalDebt = 0;

       }

    }



    protected function rules(): array
    {
        return [
            'payment.created_at' => ['nullable', 'date'],
            'payment.student_id' => ['required', 'integer', 'exists:students,id'],
            'payment.channel' => ['required', 'string'],
            'payment.amount.*' => ['nullable', 'numeric', 'min:0'],
            'payment.payer_name' => ['required', 'string'],
            'payment.payer_phone' => ['required', 'numeric']
        ];
    }
    protected function getValidationAttributes(): array
    {
        return [
         'payment.student_id' => 'Student',
        ];
    }



    public function create(): void
    {
        $this->validate();

        $student = school()->students()->findOrFail($this->payment['student_id']);
        $amounts = array_map('floatval', (array)($this->payment['amount'] ?? []));
        $totalEnteredPayment = array_sum($amounts);

        $studentSurplus = $student->surplusPayments()->sum('amount');
        $totalPayment = $totalEnteredPayment + $studentSurplus;

        if ($totalPayment <= 0) {
            $this->dispatch('error', 'Payment amount must be greater than zero');
            return;
        }

        $date = isset($this->payment['created_at'])
            ? Carbon::parse($this->payment['created_at'])
            : now();

        DB::beginTransaction();
        try {
            $remainingSurplus = $studentSurplus;

            $payment = school()->payments()->create([
                'student_id' => $student->id,
                'amount' => $totalPayment,
                'channel' => $this->payment['channel'],
                'payer_name' => $this->payment['payer_name'],
                'payer_phone' => $this->payment['payer_phone'],
                'created_at' => $date,
            ]);

            foreach ($this->bills as $index => $bill) {
                $billBalance = $bill->balance;
                $amount = $this->payment['amount'][$index] ?? 0;

                if ($amount < $billBalance && $remainingSurplus > 0) {
                    $surplusUsed = min($billBalance - $amount, $remainingSurplus);
                    $amount += $surplusUsed;
                    $remainingSurplus -= $surplusUsed;
                }

                if ($amount > $billBalance) {
                    $surplus = $amount - $billBalance;
                    $amount = $billBalance;

                    $student->surplusPayments()->create([
                        'school_id' => $student->school_id,
                        'payment_id' => $payment->id,
                        'amount' => $surplus,
                        'description' => "Surplus from payment by $payment->payer_name",
                        'created_at' => $date,
                    ]);
                }

                if ($amount > 0) {
                    school()->paymentBills()->create([
                        'payment_id' => $payment->id,
                        'bill_id' => $bill->id,
                        'amount_paid' => $amount,
                        'created_at' => $date,
                    ]);
                }
            }
            if ($studentSurplus > $remainingSurplus) {
                $usedSurplus = $studentSurplus - $remainingSurplus;
                $student->deductSurplus($usedSurplus, "Used for payment by $payment->payer_name");
            }

            $this->sendPaymentSMS($student, $totalPayment, $this->payment['payer_phone']);
            DB::commit();

            $this->printOut = $payment;
            $this->reset('payment', 'bills');
            $this->dispatch('success', 'Student fee paid successfully.');
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
            $this->dispatch("error", 'Something went wrong');
        }
    }




    public function sendPaymentSMS(Student $student,$amount, $payer_phone): void
    {
        if ($student->school->communication->send_after_payment){
            $recipient = '';
            foreach($student->parents as $parent){
                $recipient = $payer_phone == $parent->phone
                    ? $payer_phone
                    : "$payer_phone,$parent->phone";
            }
            $student->school->broadCastSMS(
                $recipient,
                "We have received a payment of GHS $amount for your ward {$this->student->fullname}, Thank you.",
            );
        }

    }


    public function render()
    {
        return view('livewire.fee.fee-payment', [
            'students' => $this->getStudents(['bills'])
        ]);
    }
}
