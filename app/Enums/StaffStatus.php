<?php

namespace App\Enums;

enum StaffStatus: string
{
    case ACTIVE = 'active';
    case STOPPED = 'stopped';
    case SUSPENDED = 'suspended';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
