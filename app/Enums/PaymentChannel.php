<?php

namespace App\Enum;

enum PaymentChannel: string

{
    case MANUAL = 'manual';
    case BANK = 'bank';

}
