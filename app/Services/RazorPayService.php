<?php

namespace App\Services;

use Razorpay\Api\Api;
use Exception;

class RazorPayService
{
    public function paymentProcess($request, $config)
    {
        try {
            $api = new Api($config->key, $config->secret);
            $paymentId = $request->token_id;
            $payment = $api->payment->fetch($paymentId);
            if ($payment->status === 'authorized' && !$payment->captured) {

                $captureResponse = $api->payment->fetch($paymentId)
                                 ->capture(['amount' => $payment->amount]);

                if ($captureResponse->status === 'captured') {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $ex) {
            return false;
        }
    }
}
