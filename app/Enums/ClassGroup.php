<?php

namespace App\Enums;


enum ClassGroup: string
{
    case CRECHE = 'CRECHE';
    case NURSERY_ONE = 'NURSERY ONE';
    case NURSERY_TWO = 'NURSERY TWO';
    case KG_ONE = 'KG ONE';
    case KG_TWO = 'KG TWO';
    case BASIC_ONE = 'BASIC ONE';
    case BASIC_TWO = 'BASIC TWO';
    case BASIC_THREE = 'BASIC THREE';
    case BASIC_FOUR = 'BASIC FOUR';
    case BASIC_FIVE = 'BASIC FIVE';
    case BASIC_SIX = 'BASIC SIX';
    case JHS_ONE = 'JHS ONE';
    case JHS_TWO = 'JHS TWO';
    case JHS_THREE = 'JHS THREE';
    case SHS_ONE = 'SHS ONE';
    case SHS_TWO = 'SHS TWO';
    case SHS_THREE = 'SHS THREE';
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
