<?php

namespace App\Traits;

use App\Notifications\ResetPasswordNotification;

trait PasswordReset
{
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

}
