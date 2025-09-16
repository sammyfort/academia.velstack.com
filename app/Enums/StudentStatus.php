<?php

namespace App\Enums;

enum StudentStatus: string
{
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case STOPPED = 'stopped';
    case SUSPENDED = 'suspended';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
