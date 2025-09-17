<?php

namespace App\Enums;

enum Title: string
{
    case MR = 'Mr';
    case MRS = 'Mrs';
    case MISS = 'Miss';
    case DR = 'Dr';
    case PROF = 'Prof';
    case ING = 'Ing';
    case OTHER = 'Other';
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

}
