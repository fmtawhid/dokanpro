<?php

namespace App\Enums;

enum DateFormat: string
{
    case DMY = 'd-m-Y';
    case MDY = 'm-d-Y';
    case YDM = 'Y-d-m';
    case YMD = 'Y-m-d';
    case D_MY = 'd_M,Y';
    case M_DY = 'M_d,Y';
}
