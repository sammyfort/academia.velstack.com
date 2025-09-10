<?php

namespace App\Enum;

enum TransferStatus: string
{

    case PENDING = 'pending';
    case REJECTED = 'rejected';
    case APPROVED = 'approved';
}
