<?php

namespace App\Enums;

enum PaymentGateway: string
{
    case STRIPE = 'stripe';
    case PAYPAL = 'paypal';
    case PAYSTACK = 'paystack';
    case PAYTAB = 'paytab';
    case RAZORPAY = 'razorpay';
    case PAYFAST = 'payfast';
    case ORANGEPAY = 'orangepay';
    case MANUAL = 'manual';

}
