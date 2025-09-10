<?php

namespace App\Enums;

enum SchoolPosition: string
{
    case MANAGER = 'manager';
    case TEACHER = 'teacher';
    case HEADTEACHER = 'headteacher';

    case PROPRIETOR = 'proprietor';
}
