<?php

namespace App\Enums;

enum BillType: string
{
    case STUDENT = 'student';
    case CLASSROOM = 'class';
    case MASS = 'mass';
}
