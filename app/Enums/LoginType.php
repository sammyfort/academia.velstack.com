<?php

namespace App\Enums;

enum LoginType: string
{
    case STAFF = 'staff';
    case PARENT = 'parent';
    case ADMIN = 'user';


     public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
    
}
