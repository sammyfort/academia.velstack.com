<?php

namespace App\Enums;

enum SMSReceivers: string
{
    case PARENTS = 'parents';
    case STUDENTS = 'students';
    case STAFF = 'staff';
    case INDIVIDUAL = 'individual';

}
