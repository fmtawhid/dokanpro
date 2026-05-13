<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Enums\IsHas;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionRequest;
use App\Repositories\ShopSubscriptionRepository;
use App\Repositories\SubscriptionRequestRepository;
use App\Repositories\PaymentGatewayRepository;
use App\Repositories\OrangePayRepository;
use App\Http\Requests\PaymentGatewayRequest;
use App\Models\PaymentGateway;
use App\Enums\PaymentGateway as PaymentGatewayEnum;
use Illuminate\Http\Request;
use App\Services\StripePayService;
use App\Services\PaypalService;
use App\Services\RazorPayService;
use App\Models\Payment;
use App\Services\PaystackService;
use App\PaymentMethod\OrangeMoney;
use App\Services\OrangePayService;
use App\Services\PayfastService;
use Illuminate\Support\Facades\Http;
use Exception;

class PaymentGatewayController extends Controller
{
    public function __construct(
        protected StripePayService $stripeService,
        protected RazorPayService $razorpayService,
        protected PaypalService $paypalService,
        protected PaystackService $paystackService,
        protected OrangePayService $orangepayService,
        protected PayfastService $payfastService,
    ) {}

    /**
     * Show payment gateway
     */
    public function index()
    {
        $paymentGateways = PaymentGateway::where('shop_id', null)->get();
        return view('paymentGateway.index', compact('paymentGateways'));
    }

    /**
     * Update payment gateway
     */
    public function update(PaymentGatewayRequest $request, PaymentGateway $paymentGateway)
    {
        PaymentGatewayRepository::updateByRequest($request, $paymentGateway);

        return back()->withSuccess(__('Payment Gateway Updated Successfully'));
    }

    /**
     * Toggle payment gateway status
     */
    public function toggle(PaymentGateway $paymentGateway)
    {
        $paymentGateway->update([
            'is_active' => ! $paymentGateway->is_active,
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
            // Store selected features in the subscription request
            $selectedFeatures = $request->input('features', []);
            $subscriptionRequest->update([
                'selected_features' => $selectedFeatures,
            ]);

            // Handle manual payment - create pending subscription for admin approval
            if ($request->payment_method === 'manual') {
                $shopSubscription = ShopSubscriptionRepository::query()->where([
                    'is_current' => IsHas::YES->value,
                    'shop_id'=> $this->mainShop()->id
                ])->first();

                if ($shopSubscription) {
                    $shopSubscription->update([
                        'is_current' => IsHas::NO->value,
                    ]);
                }
                
                // Update subscription request with manual gateway and pending status
                $subscriptionRequest->update([
                    'payment_gateway' => 'manual',
                    'payment_status' => 'Unpaid',
                    'status' => 'Pending',
                ]);
                
                $createdSubscription = ShopSubscriptionRepository::storePendingByRequest($subscriptionRequest, 'manual');
                
                // Attach selected features to the shop subscription
                if (!empty($selectedFeatures)) {
                    $featuresData = [];
                    foreach ($selectedFeatures as $featureId) {
                        $featurePriceData = $subscriptionRequest->subscription->features()
                            ->where('subscription_features.id', $featureId)
                            ->first();
                        if ($featurePriceData) {
                            $featuresData[$featureId] = [
                                'price' => $featurePriceData->pivot->price,
                                'expired_at' => $createdSubscription->expired_at,
                            ];
                        }
                    }
                    if (!empty($featuresData)) {
                        $createdSubscription->features()->attach($featuresData);
                    }
                }

                return redirect()->route('root')->with('success', 'Subscription request submitted. Waiting for admin approval.');
            }

            // Handle other payment gateways
            $paymentGateway = PaymentGateway::where('name', $request->payment_method)->where('shop_id', null)->first();
            $config = json_decode($paymentGateway->config);
            $request['paid_amount'] = $subscriptionRequest->subscription->price ??  null;
            $request['description'] = $subscriptionRequest->subscription->description ?? null;
            $request['mode'] = $paymentGateway->mode ?? null;
            $this->{$request->payment_method . 'Service'}->paymentProcess($request, $config);
            $shopSubscription = ShopSubscriptionRepository::query()->where([
                'is_current' => IsHas::YES->value,
                'shop_id'=> $this->mainShop()->id
            ])->first();

            if ($shopSubscription) {
                $shopSubscription->update([
                    'is_current' => IsHas::NO->value,
                ]);
            }
            SubscriptionRequestRepository::updateByRequest($subscriptionRequest, $request->payment_method);
            $createdSubscription = ShopSubscriptionRepository::storeByRequest($subscriptionRequest, $request->payment_method);
            
            // Attach selected features to the shop subscription
            if (!empty($selectedFeatures)) {
                $featuresData = [];
                foreach ($selectedFeatures as $featureId) {
                    $featurePriceData = $subscriptionRequest->subscription->features()
                        ->where('subscription_features.id', $featureId)
                        ->first();
                    if ($featurePriceData) {
                        $featuresData[$featureId] = [
                            'price' => $featurePriceData->pivot->price,
                            'expired_at' => $createdSubscription->expired_at,
                        ];
                    }
                }
                if (!empty($featuresData)) {
                    $createdSubscription->features()->attach($featuresData);
                }
            }

            return redirect()->route('root')->with('success', 'Payment successfully processed.');
        } catch (Exception $ex) {
            SubscriptionRequestRepository::requestFailed($subscriptionRequest);
            return redirect()->route('subscription.purchase.index')->withError($ex->getMessage());
        }
    }

    public function paymentNotify(Request $request)
    {
        $merchantId = $request->input('merchant_id');
        $merchantKey = $request->input('merchant_key');
        $transactionId = $request->input('transaction_id');
        $paymentStatus = $request->input('payment_status');
        $amount = $request->input('amount');
        $itemName = $request->input('item_name');
        $emailAddress = $request->input('email_address');
        if ($paymentStatus === 'success') {
            return response()->json(['status' => 'success', 'transaction_id' => $transactionId]);
        }
        return response()->json(['status' => 'failure', 'message' => 'Payment failed']);
    }

    public function orangeMoneyPaymentprocess(Request $request, SubscriptionRequest $subscriptionRequest)
    {
        $request->validate([
            'pin_code' => 'required',
            'phone_number' => 'required'
        ]);

        // Store selected features in the subscription request
        $selectedFeatures = $request->input('features', []);
        $subscriptionRequest->update([
            'selected_features' => $selectedFeatures,
        ]);

        $paymentGateway = PaymentGateway::where('name', $request->payment_method)->where('shop_id', null)->first();
        $orangePay = json_decode($paymentGateway->config);
        if (!$orangePay) {
            return to_route('subscription.purchase.update', $subscriptionRequest->subscription->id)->withError('Orange money payment gateway not configured please contact to the admin');
        }
        $orangeMoney = new OrangeMoney($orangePay->client_id, $orangePay->client_secret);
        $responseOtp = $orangeMoney->getOtp($request->pin_code, $request->phone_number, $orangePay);
        if (!isset($responseOtp['otp'])) {
            return to_route('subscription.purchase.update', $subscriptionRequest->subscription->id)->withError('Invalid pin code or phone number for orange money, please check and try again');
        }
        $response = $orangeMoney->makePayment($orangePay->merchant_code, $request->phone_number, $responseOtp['otp'], $subscriptionRequest->subscription->price, $orangePay);
        if ($response['status'] == 'SUCCESS') {
            $shopSubscription = ShopSubscriptionRepository::query()->where([
                'is_current' => IsHas::YES->value,
                'shop_id' => $this->mainShop()->id
            ])->first();
            if ($shopSubscription) {
                $shopSubscription->update([
                    'is_current' => IsHas::NO->value,
                ]);
            }
            SubscriptionRequestRepository::updateByRequest($subscriptionRequest);
            $createdSubscription = ShopSubscriptionRepository::storeByRequest($subscriptionRequest,  PaymentGatewayEnum::ORANGEPAY->value);
            
            // Attach selected features to the shop subscription
            if (!empty($selectedFeatures)) {
                $featuresData = [];
                foreach ($selectedFeatures as $featureId) {
                    $featurePriceData = $subscriptionRequest->subscription->features()
                        ->where('subscription_features.id', $featureId)
                        ->first();
                    if ($featurePriceData) {
                        $featuresData[$featureId] = [
                            'price' => $featurePriceData->pivot->price,
                            'expired_at' => $createdSubscription->expired_at,
                        ];
                    }
                }
                if (!empty($featuresData)) {
                    $createdSubscription->features()->attach($featuresData);
                }
            }

            return to_route('root')->with('success', 'Orange Money payment successfully done');
        } else {
            SubscriptionRequestRepository::requestFailed($subscriptionRequest);
            return to_route('subscription.purchase.index')->withError('Something is wrong please try again');;
        }
    }



}
