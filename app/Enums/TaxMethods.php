<?php

namespace App\Enums;

enum TaxMethods: string
{
    case EXCLUSIVE = 'Exclusive';
    case INCLUSIVE = 'Inclusive';
}