<?php

namespace App\Enums;

enum StaffEstimation: string
{
    case BELOW_500 = 'BELOW 50';
    case ABOVE_500 = 'ABOVE 10';

    case BELOW_1000 = 'BELOW 100';
    case ABOVE_1000 = 'ABOVE 100';

}
