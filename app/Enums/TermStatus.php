<?php

namespace App\Enums;

enum TermStatus: string
{
    case ACTIVE = 'active';
    case ENDED = 'ended';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
