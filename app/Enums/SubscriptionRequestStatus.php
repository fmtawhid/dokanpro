<?php

namespace App\Enums;

enum SubscriptionRequestStatus: string
{
    case PENDING = 'Pending';
    case SUCCESS = 'Success';
    case FAILED = 'Failed';
}
