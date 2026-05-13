<?php

namespace App\Enums;

enum RecurringType: string
{
    case WEEKLY = 'Weekly';
    case MONTHLY = 'Monthly';
    case YEARLY = 'Yearly';
}