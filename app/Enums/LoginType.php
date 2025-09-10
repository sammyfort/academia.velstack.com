<?php

namespace App\Enums;

enum LoginType: string
{
    case STAFF = 'staff';
    case PARENT = 'parent';
    case ADMIN = 'user';


}
