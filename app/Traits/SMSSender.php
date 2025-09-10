<?php

namespace App\Traits;

use App\Jobs\SMSSenderJob;

trait SMSSender
{
    public function send($recipient, $message):void
    {
        dispatch(new SMSSenderJob(
            $message,
            $recipient,
            'VELSTACK',
            config('app.sms_token'),
            'OpenPortal Application Confirmation',
        ))->afterCommit();

    }

}
