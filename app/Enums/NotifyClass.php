<?php

namespace App\Enums;

enum NotifyClass: string
{
    case PARENTS = 'parents';
    case STUDENTS = 'students';
    case STAFF = 'staff';
    case ALL = 'all';
    case NONE  = 'none';

}
