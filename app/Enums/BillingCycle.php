<?php

namespace App\Enums;

enum BillingCycle: string
{

    case TERMLY = 'Termly';
    case MONTHLY = 'Monthly';
    case WEEKLY = 'Weekly';

    case DAILY = 'Daily';
}
