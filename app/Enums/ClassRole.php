<?php

namespace App\Enums;

enum ClassRole: string
{
    case CLASS_TEACHER = 'class teacher';
    case SUBJECT_TEACHER = 'subject teacher';
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
