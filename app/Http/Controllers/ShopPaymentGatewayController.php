<?php

namespace App\Http\Controllers;

use App\Enums\IsHas;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionRequest;
use App\Repositories\ShopSubscriptionRepository;
use App\Repositories\SubscriptionRequestRepository;
use App\Repositories\PaymentGatewayRepository;
use App\Http\Requests\PaymentGatewayRequest;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use App\Services\StripePayService;
use App\Services\PaypalService;
use App\Services\RazorPayService;
use App\Services\PaystackService;
use App\Models\Shop;
use Exception;

class ShopPaymentGatewayController extends Controller
{
    public function __construct(
        protected StripePayService $stripepayService,
        protected RazorPayService $razorPayService,
        protected PaypalService $paypalService,
        protected PaystackService $paystackService
    ) {}

    /**
     * Show payment gateway
     */
    public function index()
    {
        $paymentGateway = PaymentGatewayRepository::getAll();
        $data['paymentGateways'] = $paymentGateway->unique('name');
        $shop = Shop::where('user_id', auth()->user()->id)->first();
        $data['shopWisePaymentGateway'] = PaymentGateway::where('shop_id', $shop->id)->get();

        return view('shopPaymentGateway.index', $data);
    }

    /**
     * Update payment gateway
     */
    public function update(PaymentGatewayRequest $request, PaymentGateway $paymentGateway)
    {
        PaymentGatewayRepository::storeOrUpdate($request, $paymentGateway);

        return back()->withSuccess(__('Payment Gateway Updated Successfully'));
    }

    /**
     * Toggle payment gateway status
     */
    public function toggle($paymentGateway)
    {

        $payment_gateway= PaymentGateway::where('id', $paymentGateway)->first();
        if($payment_gateway->shop_id == null):
            return back()->withError(__('Please First Add  Your Credential'));
        endif;
        $payment_gateway->update([
            'is_active' => ! $payment_gateway->is_active,
        ]);

        return back()->withSuccess(__('Status Updated Successfully'));
    }

    public function payment()
    {
        return view('subscriptionPurchase.payment');
    }

    public function process(Request $request, SubscriptionRequest $subscriptionRequest)
    {
        try {
            $paymentGateway = PaymentGateway::where('name', $request->payment_method)->where('shop_id', null)->first();
            $config = json_decode($paymentGateway->config);
            $request['paid_amount'] = $subscriptionRequest->subscription->price ??  null;
            $request['description'] = $subscriptionRequest->subscription->description ?? null;
            $request['mode'] = $paymentGateway->mode ?? null;
            $this->{$request->payment_method . 'Service'}->paymentProcess($request, $config);
            $shopSubscription = ShopSubscriptionRepository::query()->where([
                'is_current' => IsHas::YES->value,
                'shop_id' => $this->mainShop()->id
            ])->first();

            if ($shopSubscription) {
                $shopSubscription->update([
                    'is_current' => IsHas::NO->value,
                ]);
            }
            SubscriptionRequestRepository::updateByRequest($subscriptionRequest, $request->payment_method);
            ShopSubscriptionRepository::storeByRequest($subscriptionRequest, $request->payment_method);

            return redirect()->route('root')->with('success', 'Payment successfully processed.');
        } catch (Exception $ex) {
            SubscriptionRequestRepository::requestFailed($subscriptionRequest);
            return redirect()->route('subscription.purchase.index')->withError($ex->getMessage());
        }
    }

}
