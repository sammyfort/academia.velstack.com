<?php

namespace App\Traits;

use Masmerise\Toaster\Toaster as BaseToaster;

trait Toaster
{
    public function alert($event, $message): \Masmerise\Toaster\PendingToast
    {
       return match ($event) {
            'success' => BaseToaster::success($message),
            'warning' => BaseToaster::warning($message),
            'info' => BaseToaster::info($message),
            default => BaseToaster::error($message),
        };

    }

}
