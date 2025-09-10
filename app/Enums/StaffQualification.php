<?php

namespace App\Enums;

enum StaffQualification: string
{

    case WASSCE = 'wassce';
    case DIPLOMA = 'diploma';
    case HND = 'hnd';
    case BACHELOR = 'bachelor';
    case MASTER = 'master';
    case DOCTOR = 'doctor';
    case PHD = 'phd';

}
