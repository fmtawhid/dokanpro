<?php

namespace App\Services;

use App\Repositories\GeneralSettingRepository;
use App\Models\PaymentGateway;
use Stripe;

class StripePayService
{
    public function paymentProcess($request, $config)
    {
        $paymentGateway = PaymentGateway::where('name', $request->payment_method)->where('shop_id', null)->first();
        $config  = json_decode($paymentGateway->config);
        $generalsettings = GeneralSettingRepository::query()->whereNull('shop_id')->first();

        Stripe\Stripe::setApiKey($config->secret_key);

        $result = Stripe\Charge::create([
            "amount"  => $request->paid_amount * 100,
            "currency" => $generalsettings?->defaultCurrency?->code ?? 'USD',
            "source" => $request->token_id,
            "description" => $request->description ?? '',
        ]);
        if(($result['status'] == 'succeeded')):
            return true;
        else:
            return false;
        endif;
    }
}

