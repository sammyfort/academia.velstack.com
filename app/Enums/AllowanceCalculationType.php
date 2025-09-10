<?php

namespace App\Enums;

enum AllowanceCalculationType: string
{
    case FLAT_RATE = 'Flat Rate';
    case PERCENTAGE = 'Percentage';

}
