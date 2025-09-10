<?php

namespace App\Enums;

enum FeeType: string
{
    case STUDENT = 'student';
    case CLASSROOM = 'class';
    case MASS = 'mass';

    case TRANSPORTATION = 'transportation';

    case NONE = 'none';

}
