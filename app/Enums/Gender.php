<?php

namespace App\Enums;

enum Gender: string
{

    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

}
