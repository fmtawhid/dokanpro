<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentGateway::truncate();
        $paymentMethods = [
            [
                'title'             => 'Stripe',
                'name'              => 'stripe',
                'config'            => json_encode([
                    'secret_key'    => '',
                    'published_key' => '',
                ]),
                'mode'              => 'test',
                'alias'             => 'stripe',
                'is_active'         => true,
            ],
            [
                'title'             => 'PayPal',
                'name'              => 'paypal',
                'config'            => json_encode([
                    'client_id'     => 'AfQ5QcPh5sYCjLfMIghVO_rwBAVyG1GjCiTpAb4aNxS5rgoRu7L9TLrQYqm8z1zszb5sXL_0NW5-T658',
                    'client_secret' => 'EGDDuVCIfx6MwC-NL64Lj4Fh43_cdUAHuqj0vo3ezi_kVe3qvGDeTX--DtrCrHI2owu0QUQAKFLoxWk-',
                ]),
                'mode'              => 'test',
                'alias'             => 'paypal',
                'is_active'         => true,
            ],
            [
                'title'             => 'Razorpay',
                'name'              => 'razorpay',
                'config'            => json_encode([
                    'key'           => 'rzp_test_k23Mr4BskGqpBu',
                    'secret'        => 'LTrXh7U5xWeZoAHcqdhemFkg',
                ]),
                'mode'              => 'test',
                'alias'             => 'razorpay',
                'is_active'         => true,
            ],
            [
                'title'             => 'Paystack',
                'name'              => 'paystack',
                'config'            => json_encode([
                    'public_key'    => '',
                    'secret_key'    => '',
                    'machant_email' => '',
                ]),
                'mode'              => 'test',
                'alias'             => 'paystack',
                'is_active'         => true,
            ],

            [
                'title'             => 'OrangePay',
                'name'              => 'orangepay',
                'config'            => json_encode([
                    'client_id'     => '4d2ceeaa-8ceb-4841-a3a9-4f8a05702092',
                    'client_secret' => 'bd912517-9db0-4770-9806-61b28414f369',
                    'merchant_code' => 'bd912517-9db0-4770-9806',
                ]),
                'mode'              => 'test',
                'alias'             => 'orangepay',
                'is_active'         => true,
            ],

            [
                'title'             => 'PayFast',
                'name'              => 'payfast',
                'config'            => json_encode([
                    'merchant_id'   => '10036184',
                    'merchant_key'  => 'iz2owp36ngf2n',
                ]),
                'mode'              => 'test',
                'alias'             => 'payfast',
                'is_active'         => true,
            ],
        ];

        PaymentGateway::insert($paymentMethods);
    }
}
