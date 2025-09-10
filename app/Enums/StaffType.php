<?php

namespace App\Enums;

enum StaffType: string
{
    case ADMINISTRATIVE_STAFF = 'Administrative Staff';
    case ACCOUNTING_STAFF = 'Accounting Staff';
    case TEACHING_STAFF = 'Teaching Staff';
    case NON_TEACHING_STAFF = 'Non-Teaching Staff';

}
