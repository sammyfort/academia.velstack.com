<?php

namespace App\Enums;

enum PayslipStatus: string
{

    case PENDING = 'PENDING';
    case PAID = 'PAID';
    case CANCELLED = 'CANCELLED';


}
