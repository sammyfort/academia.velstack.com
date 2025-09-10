<?php

namespace App\Observers;

use App\Models\Subscription;
use Illuminate\Support\Facades\Cache;

class SubscriptionObserver
{
    public function updated(Subscription $subscription): void
    {
        Cache::forget("subscription_status:$subscription->school_id");

    }
}
