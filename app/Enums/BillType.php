<?php

namespace App\Enums;

enum BillType: string
{
    case STUDENT = 'student';
    case CLASSROOM = 'class';
    case MASS = 'mass';
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
