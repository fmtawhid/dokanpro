<?php

namespace App\Enums;

enum Roles: string
{
   case SUPERADMIN = 'super admin';
   case ADMIN = 'admin';
   case OWNER = 'owner';
   case STORE = 'store';
   case CUSTOMER = 'customer';
}
