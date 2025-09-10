<?php

namespace App\Traits;

use App\Models\School;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;

trait SubscriptionTrait
{
    public function create(School $school, $data)
    {

        return $school->transactions()->create([
            'amount' => $data['amount'],
            'reference' => $data['reference'],
            'channel' => $data['channel'],
            'status' => $data['status'],
            'response' => $data['response'],
            'payment_number' => $data['payment_number'],
        ]);

    }
    public function processSubscription($json): void
    {
        $payload = $json['data'];
        if (isset($payload['status']) && isset($payload['metadata']['school_id'])) {

            $transaction = Transaction::query()->where('reference',$payload['reference'])->first();
            if (!$transaction){
                if ($payload['status'] === 'success'){
                    $school = School::findOrFail($payload['metadata']['school_id']);
                    $data = [
                        'amount' => $payload['amount'],
                        'reference' => $payload['reference'],
                        'channel' => $payload['authorization']['bank'],
                        'status' => $payload['status'],
                        'response' => $payload['gateway_response'],
                        'payment_number' => $payload['authorization']['bin']
                    ];

                    DB::beginTransaction();
                    try {
                        $this->create($school, $data);
                        $school->subscription()->update(['expires_at' => now()->addDays(90)]);
                        DB::commit();
                    }catch (Exception $exception){
                        info($exception);
                        DB::rollBack();
                    }
                }
            }

        }
    }

}
