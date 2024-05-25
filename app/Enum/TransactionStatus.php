<?php

namespace App\Enum;

enum TransactionStatus: string
{
    case Pending = 'pending';
    case Verified = 'verified';
    case Failed = 'failed';


}
