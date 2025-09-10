<?php

namespace App\Enums;

enum Religion: string
{
    case CHRISTIAN = 'Christian';
    case HINDU = 'Hindu';
    case ISLAM = 'Islam';
    case TRADITIONAL = 'Traditional';

    case OTHER = 'Other';

}
