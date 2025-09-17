<?php

namespace App\Enums;

enum StaffExperience: string
{
    case BEGINNER = 'beginner';          // Less than 1 year
    case INTERMEDIATE = 'intermediate'; // 1 to 5 years
    case EXPERIENCED = 'experienced';   // 6 to 10 years
    case EXPERT = 'expert';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

}
