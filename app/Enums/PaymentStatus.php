<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PAID = 'Paid';
    case UNPAID = 'Unpaid';
}
