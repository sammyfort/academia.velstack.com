<?php

namespace App\Enums;

enum ExpenseCategory: string
{
    case SALARIES = 'Salaries';
    case RENT = 'Rent';
    case UTILITIES = 'Utilities';
    case OFFICE_SUPPLIES = 'Office Supplies';
    case TRAVEL = 'Travel';
    case MARKETING = 'Marketing';
    case TRAINING = 'Training';
    case INSURANCE = 'Insurance';
    case MISCELLANEOUS = 'Miscellaneous';
    case MAINTENANCE = 'Maintenance';
}
