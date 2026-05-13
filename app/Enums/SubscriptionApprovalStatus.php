<?php

namespace App\Enums;

enum SubscriptionApprovalStatus: string
{
    case PENDING = 'Pending';
    case APPROVED = 'Approved';
    case REJECTED = 'Rejected';
}
