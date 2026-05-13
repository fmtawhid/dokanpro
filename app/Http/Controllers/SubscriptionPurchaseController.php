<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Repositories\SubscriptionRepository;
use App\Repositories\SubscriptionRequestRepository;
use App\Repositories\PaymentGatewayRepository;
use App\Repositories\SubscriptionFeatureRepository;
use App\Models\PaymentGateway;

class SubscriptionPurchaseController extends Controller
{
    public function index()
    {
        $subscriptions = SubscriptionRepository::query()->where('status', 'Active')->get();
        return view('subscriptionPurchase.index', compact('subscriptions'));
    }

    public function update(Subscription $subscription)
    {
        $stripeGateway  = PaymentGateway::where('name', 'stripe')->where('shop_id', null)->first();
        $razorpayGateway = PaymentGateway::where('name', 'razorpay')->where('shop_id', null)->first();
        $payStackGateway = PaymentGateway::where('name', 'paystack')->where('shop_id', null)->first();
        $payPalGateway = PaymentGateway::where('name', 'paypal')->where('shop_id', null)->first();
        $payfastGateway = PaymentGateway::where('name', 'payfast')->where('shop_id', null)->first();
        $stripe_publish_key = optional(json_decode(optional($stripeGateway)->config))->published_key ?? '';
        $razorpay_publish_key = optional(json_decode(optional($razorpayGateway)->config))->key ?? '';
        $paystack_publish_key = optional(json_decode(optional($payStackGateway)->config))->public_key ?? '';
        $paypal_client_id = optional(json_decode(optional($payPalGateway)->config))->client_id ?? '';
        $payfast_client_id = optional(json_decode(optional($payfastGateway)->config))->merchant_id ?? '';
        $payfast_client_secret = optional(json_decode(optional($payfastGateway)->config))->merchant_key ?? '';
        
        // Get subscription features with their prices
        $featuresData = $subscription->features()->get();
        
        $data = [
            'subscriptionRequest' => SubscriptionRequestRepository::storeByRequest($subscription),
            'paymentGateways' => PaymentGatewayRepository::query()->where('is_active', 1)->where('shop_id', null)->get(),
            'subscription' => $subscription,
            'features' => $featuresData,
            'stripe_publish_key' => $stripe_publish_key,
            'razorpay_publish_key' => $razorpay_publish_key,
            'paystack_publish_key' => $paystack_publish_key,
            'paypal_client_id' => $paypal_client_id,
            'payfast_client_id' => $payfast_client_id,
            'payfast_client_secret' => $payfast_client_secret,
        ];

        return view('subscriptionPurchase.payment', $data);
    }
}

