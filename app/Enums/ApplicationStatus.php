<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';
    case ONHOLD = 'ONHOLD';

    case PENDING = 'PENDING';
}
