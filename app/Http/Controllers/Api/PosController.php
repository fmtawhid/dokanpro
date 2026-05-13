<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleRequest;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CustomerGroupResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\TaxResource;
use App\Http\Resources\WarehouseResource;
use App\Http\Resources\PaymentGatewayResource;
use App\Models\GeneralSetting;
use App\Repositories\BrandRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CustomerGroupRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\GeneralSettingRepository;
use App\Repositories\ProductRepository;
use App\Repositories\SaleRepository;
use App\Repositories\TaxRepository;
use App\Repositories\WarehouseRepository;
use Illuminate\Support\Facades\Http;
use App\Models\PaymentGateway;
use Razorpay\Api\Api;
use App\Models\Shop;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;


class PosController extends Controller
{
    public function pos()
    {
        $customers = CustomerRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        $customerGroups = CustomerGroupRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        $warehouses = WarehouseRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        $taxes = TaxRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        $products = ProductRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->whereNotNull('is_featured')->get();
        $brands = BrandRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        $categories = CategoryRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        $generalSetting = GeneralSetting::where('shop_id', $this->mainShop()->id)->first();
        $currency = $generalSetting->defaultCurrency?->symbol ?? '$';
        $barcodeDigits = $generalSetting->barcode_digits ?? 8;

        return $this->json('Pos data', [
            'customers' => CustomerResource::collection($customers),
            'customerGroups' => CustomerGroupResource::collection($customerGroups),
            'warehouses' => WarehouseResource::collection($warehouses),
            'categories' => CategoryResource::collection($categories),
            'taxes' => TaxResource::collection($taxes),
            'featuredProducts' => ProductResource::collection($products),
            'brands' => BrandResource::collection($brands),
            'currency' => $currency,
            'barcodeDigits' => $barcodeDigits,
        ]);
    }

    public function gatewayList()
    {
        $shop = Shop::where('user_id', auth()->user()->id)->first();
        $paymentGateways = PaymentGateway::where('shop_id', $shop->id)->get();

        return $this->json("Payment Gateway", [
            'paymentGatewayResources' => PaymentGatewayResource::collection($paymentGateways),
        ]);
    }
    public function redirectPayment($request, $sale_id)
    {
        $user = auth()->user();
        $amount = $request->paid_amount;
        $redirectUrl = '';

        $successUrl = route('payment.success');
        $cancelUrl  = route('payment.cancel');

        $shop = Shop::where('user_id', $user->id)->first();
        if (!$shop) {
            return response()->json(['status' => 'error', 'message' => 'Shop not found'], 404);
        }

        $paymentGateways = PaymentGateway::where('shop_id', $shop->id)
            ->where('is_active', 1)
            ->get();
        if ($paymentGateways->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'No active payment gateways found'], 404);
        }

        $data['sales'] = SaleRepository::find(request()->id);
        $data['paymentGateways'] = $paymentGateways;

        $gatewayConfigs = [];
        foreach ($paymentGateways as $gateway) {
            $config = json_decode($gateway->config, true);
            $gatewayConfigs[$gateway->name] = $config;
        }

        switch ($request->payment_method) {
            case 'stripe':
                if (!isset($gatewayConfigs['stripe']['published_key']) || !isset($gatewayConfigs['stripe']['secret_key'])) {
                    return response()->json(['status' => 'error', 'message' => 'Stripe credentials not found'], 400);
                }

                $callbackUrl = $successUrl . '?' . http_build_query([
                    'payment' => 'stripe',
                    'request' => $sale_id,
                    'user'    => $user->id,
                ]);

                \Stripe\Stripe::setApiKey($gatewayConfigs['stripe']['secret_key']);

                $session = \Stripe\Checkout\Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'USD',
                            'product_data' => ['name' => 'Order Payment'],
                            'unit_amount' => $amount * 100,
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    // 'success_url' => $callbackUrl . '?session_id=' . '{CHECKOUT_SESSION_ID}',
                    'success_url' => $callbackUrl . '&session_id=' .  '{CHECKOUT_SESSION_ID}',

                    'cancel_url'  => $cancelUrl . '?payment=stripe',
                ]);

                $redirectUrl = $session->url;
                break;

            case 'paypal':
                if (!isset($gatewayConfigs['paypal']['client_id']) || !isset($gatewayConfigs['paypal']['client_secret'])) {
                    return response()->json(['status' => 'error', 'message' => 'PayPal credentials not found'], 400);
                }

                $paypalClientId = $gatewayConfigs['paypal']['client_id'];
                $paypalSecret   = $gatewayConfigs['paypal']['client_secret'];
                $paypalUrl      = "https://api-m.sandbox.paypal.com";

                $callbackUrl = $successUrl . '?' . http_build_query([
                    'payment' => 'paypal',
                    'request' => $sale_id,
                    'user'    => $user->id,
                ]);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "$paypalUrl/v1/oauth2/token");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
                curl_setopt($ch, CURLOPT_USERPWD, $paypalClientId . ":" . $paypalSecret);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json", "Accept-Language: en_US"]);
                $response = curl_exec($ch);
                if (!$response) {
                    return response()->json(['status' => 'error', 'message' => 'PayPal token request failed'], 500);
                }
                $accessToken = json_decode($response)->access_token;
                curl_close($ch);

                $orderData = [
                    "intent" => "CAPTURE",
                    "purchase_units" => [[
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => $amount
                        ],
                        'name'  => $user->name,
                        'email' => $user->email,
                    ]],
                    "application_context" => [
                        "return_url" => $callbackUrl,
                        "cancel_url" => $cancelUrl . '?payment=paypal'
                    ]
                ];

                $ch = curl_init("$paypalUrl/v2/checkout/orders");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "Content-Type: application/json",
                    "Authorization: Bearer $accessToken"
                ]);

                $response = curl_exec($ch);
                if (!$response) {
                    return response()->json(['status' => 'error', 'message' => 'PayPal order creation failed'], 500);
                }
                $order = json_decode($response);
                curl_close($ch);

                if (!isset($order->links)) {
                    return response()->json(['status' => 'error', 'message' => 'Failed to get PayPal approval link'], 500);
                }

                foreach ($order->links as $link) {
                    if ($link->rel === "approve") {
                        $redirectUrl = $link->href;
                        break;
                    }
                }
                if (!$redirectUrl) {
                    return response()->json(['status' => 'error', 'message' => 'PayPal approval URL not found'], 500);
                }
                break;

            case 'paystack':
                if (!isset($gatewayConfigs['paystack']['public_key']) || !isset($gatewayConfigs['paystack']['secret_key'])) {
                    return response()->json(['status' => 'error', 'message' => 'Paystack credentials not found'], 400);
                }

                $paystackSecretKey = $gatewayConfigs['paystack']['secret_key'];
                $callbackUrl = $successUrl;
                $callbackUrl .= "?payment=paystack&request=$sale_id&user=$user->id";
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $paystackSecretKey,
                    'Content-Type' => 'application/json',
                ])->post('https://api.paystack.co/transaction/initialize', [
                    'name'         => $user->name,
                    'email'        => $user->email,
                    'amount'       => $amount * 100,
                    'currency'     => 'ZAR',
                    'callback_url' => $callbackUrl,
                    'cancel_url'   => $cancelUrl,
                ]);

                $paystackResponse = $response->json();

                if (!$response->successful() || !$paystackResponse['status']) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => $paystackResponse['message'] ?? 'Payment initialization failed'
                    ], 400);
                }
                $redirectUrl = $paystackResponse['data']['authorization_url'];
                break;

                case 'razorpay':
                    // Get only the necessary request parameters
                    $successUrl = route('razorpay') . '?' . http_build_query([
                        'payment' => 'razorpay',
                        'sale_id' => $sale_id,
                        'user_id' => $user->id, // Pass user ID or any other parameters as needed
                    ]);

                    // Set the redirect URL
                    $redirectUrl = $successUrl;

                    break;


            case 'orangepay':
                if (!isset($gatewayConfigs['orangepay']['client_secret']) || !isset($gatewayConfigs['orangepay']['client_id'])) {
                    return response()->json(['status' => 'error', 'message' => 'OrangePay credentials not found'], 400);
                }

                $apiKey     = $gatewayConfigs['orangepay']['client_secret'];
                $merchantId = $gatewayConfigs['orangepay']['client_id'];
                $orangePayUrl = "https://api.orangepay.com/v1/checkout";

                $callbackUrl = $successUrl . '?' . http_build_query([
                    'payment' => 'orangepay',
                    'request' => $sale_id,
                    'user'    => $user->id,
                ]);
                $cancelUrlOrangepay = $cancelUrl . '?payment=orangepay';

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $orangePayUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                    'amount'       => $amount,
                    'currency'     => 'USD',
                    'name'         => $user->name,
                    'email'        => $user->email,
                    'merchant_id'  => $merchantId,
                    'api_key'      => $apiKey,
                    'callback_url' => $callbackUrl,
                    'cancel_url'   => $cancelUrlOrangepay,
                ]));
                curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
                $response = curl_exec($ch);

                if (!$response) {
                    return response()->json(['status' => 'error', 'message' => 'OrangePay request failed'], 500);
                }
                $responseJson = json_decode($response);
                curl_close($ch);

                if (isset($responseJson->redirect_url)) {
                    $redirectUrl = $responseJson->redirect_url;
                } else {
                    return response()->json(['status' => 'error', 'message' => 'OrangePay redirect URL not found'], 500);
                }
                break;

                case 'payfast':
                    if (!isset($gatewayConfigs['payfast']['merchant_key']) || !isset($gatewayConfigs['payfast']['merchant_id'])) {
                        return response()->json(['status' => 'error', 'message' => 'PayFast credentials not found'], 400);
                    }

                    $payfastUrl = 'https://sandbox.payfast.co.za/eng/process';
                    $apiKey     = $gatewayConfigs['payfast']['merchant_key'];
                    $merchantId = $gatewayConfigs['payfast']['merchant_id'];


                    $callbackUrl = $successUrl . '?' . http_build_query([
                        'payment' => 'payfast',
                        'request' => $sale_id,
                        'user'    => $user->id,
                    ]);

                    $data = [
                        'merchant_id'  => $merchantId,
                        'merchant_key' => $apiKey,
                        'name'         => $user->name,
                        'email'        => $user->email,
                        'amount'       => $amount,
                        'currency'     => 'ZAR',
                        'item_name'    => 'Subscription Payment',
                        'return_url'   => $callbackUrl,
                        'cancel_url'   => route('payment.cancel', ['payment' => 'payfast']),
                        'notify_url'   => $callbackUrl,
                    ];

                    $queryString = http_build_query($data);

                    $redirectUrl = $payfastUrl . '?' . $queryString;
                    break;

            default:
                return response()->json(['status' => 'error', 'message' => 'Invalid payment method'], 400);
        }
        return $redirectUrl;
    }

    public function store(SaleRequest $request)
    {
        if ($request->sale_id) {
            $draftSales = SaleRepository::find($request->sale_id);
            foreach ($draftSales->productSales as $draftProduct) {
                $draftProduct->product->update(['qty' => $draftProduct->product->qty + $draftProduct->qty]);
            }
            $draftSales->productSales()->delete();
            $draftSales->delete();
        }


        if($request->payment_method != 'Cash' || $request->type != 'Draft')
        {
            $paymentStatus = 1;

            $sale = SaleRepository::storeByRequest($request->merge(['payment_statuss' => $paymentStatus]));

           $redirectUrl = $this->redirectPayment($request, $sale->id);
           return $this->json('successfully created', [
            'redirectUrl' => $redirectUrl,
            'id' => $sale->id,

            ]);
        }else{

            $sale = SaleRepository::storeByRequest($request);

            $message = 'Product successfull sold';
            if ($request->type == 'Draft') {
                $message = 'Product successfull drafted';
            }
            return $this->json($message, [
                'draft_id' => $sale->id,
                'invoice_pdf_url' => $request->type == 'Draft' ? null : $this->downloadInvoice($sale->id),
            ]);
        }

    }

    public function successPayment(Request $request)
    {
        $user = $request->input('user');
        $paymentMethod = $request->input('payment');
        $saleId = $request->input('request') ?? $request->sale_id;
        $sale = SaleRepository::find($saleId);

        $shop = Shop::where('user_id', $user)->first();

        $amount = $sale->grand_total;

        switch ($paymentMethod) {
            case 'stripe':
                $gatewayConfigs = $this->getPaymentGatewayConfigs($shop,'stripe');

                \Stripe\Stripe::setApiKey($gatewayConfigs['secret_key']);

                $sessionId = $request->input('session_id');

                $session = \Stripe\Checkout\Session::retrieve($sessionId);

                if ($session->payment_status === 'paid') {
                    $sale->payment_status = 3;
                    $sale->save();
                    return to_route('order-payment.success');
                }
                return to_route('payment.cancel');

            case 'paypal':
                $paymentId = $request->input('paymentId');
                $payerId = $request->input('PayerID');

                $gatewayConfigs = $this->getPaymentGatewayConfigs($shop,'paypal');
                $paypalClientId = $gatewayConfigs['client_id'];
                $paypalSecret = $gatewayConfigs['client_secret'];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
                curl_setopt($ch, CURLOPT_USERPWD, $paypalClientId . ":" . $paypalSecret);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json", "Accept-Language: en_US"]);

                $response = curl_exec($ch);
                $accessToken = json_decode($response)->access_token;
                curl_close($ch);

                $ch = curl_init("https://api-m.sandbox.paypal.com/v2/checkout/orders/$paymentId");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "Content-Type: application/json",
                    "Authorization: Bearer $accessToken"
                ]);

                $response = curl_exec($ch);
                $order = json_decode($response);
                curl_close($ch);

                if ($order->status === 'COMPLETED') {
                    $sale->payment_status = 3;
                    $sale->save();
                    return to_route('order-payment.success');
                }
                return to_route('payment.cancel');

            case 'paystack':
                $paystackReference = $request->input('reference');
                $gatewayConfigs = $this->getPaymentGatewayConfigs($shop, 'paystack');
                $paystackSecretKey = $gatewayConfigs['secret_key'];
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $paystackSecretKey
                ])->get("https://api.paystack.co/transaction/verify/$paystackReference");

                $paystackResponse = $response->json();


                if ($paystackResponse['data']['status'] === 'success') {
                    $sale->payment_status = 3;
                    $sale->save();
                    return to_route('order-payment.success');
                }
                return to_route('payment.cancel');
            case 'razorpay':
                $razorpayPaymentId = $request->input('payment_id');

                $gatewayConfigs = $this->getPaymentGatewayConfigs($shop, 'razorpay');
                $razorpayKey = $gatewayConfigs['key'];
                $razorpaySecretKey = $gatewayConfigs['secret'];

                $api = new Api($razorpayKey, $razorpaySecretKey);

                $payment = $api->payment->fetch($razorpayPaymentId);

                if ($payment->status === 'captured') {

                    $sale->payment_status = 3;
                    $sale->save();
                    return to_route('order-payment.success');
                }
                return to_route('payment.cancel');


            case 'payfast':
                $payfastPaymentId = $request->input('pf_payment_id');
                $paymentStatus = $request->input('payment_status');
                $signature = $request->input('signature');
                $gatewayConfigs = $this->getPaymentGatewayConfigs($shop, 'payfast');
                $paymentStatus = $this->verifyPayFastPayment($saleId, $gatewayConfigs);

                if ($paymentStatus == 'success') {
                    $sale->payment_status = 3;
                    $sale->save();
                    return to_route('order-payment.success');
                }
                return to_route('payment.cancel');

            case 'orangepay':
                $orangePayTransactionId = $request->input('transaction_id');
                $orangePayStatus = $request->input('status');
                $orangePaySignature = $request->input('signature');

                if (strtolower($orangePayStatus) === 'success' && !empty($orangePayTransactionId)) {
                    $sale->payment_status = 3;
                    $sale->save();
                    return to_route('order-payment.success');
                }
                return to_route('payment.cancel');
            default:
                return response()->json(['status' => 'error', 'message' => 'Invalid payment method'], 400);
        }
    }


    public function verifyPayFastPayment($sale_id, $gatewayConfigs)
    {
        $paymentUrl = 'https://sandbox.payfast.co.za/eng/query/validate';
        $queryData = [
            'merchant_id'  => $gatewayConfigs['merchant_id'],
            'merchant_key' => $gatewayConfigs['merchant_key'],
            'sale_id'      => $sale_id,
            'signature'    => md5($gatewayConfigs['merchant_id'] . $gatewayConfigs['merchant_key'] . $sale_id),
        ];

        $response = Http::asForm()->post($paymentUrl, $queryData);

        if ($response->successful()) {
            return 'success';
        } else {
            return 'failed';
        }
    }


    public function details($id)
    {
        $sale = SaleRepository::query()->where('id', $id)->first();
        $products = [];
        foreach ($sale->productSales as $productSales) {
            $products[] = ProductResource::make($productSales->product);
        }
        return $this->json('Product successfull drafted', [
            'products' => $products,
        ]);
    }
    private function downloadInvoice($id)
    {
        $sale = SaleRepository::find($id);
        if (!$sale) {
            return $this->json('Sale id not found', [], 422);
        }
        $generalsettings = GeneralSettingRepository::query()->where('shop_id', $this->mainShop()->id)->first();

        $pdf = Pdf::loadView('sale.invoice', compact('sale', 'generalsettings'));

        $storagePath = storage_path('app/public/invoices');
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
        }
        $pdfPath = $storagePath . '/' . $id . '.pdf';
        $pdf->save($pdfPath);
        $path = asset('storage/invoices/' . $id . '.pdf');
        return $path;
    }

    private function getPaymentGatewayConfigs($shop, $payment_method)
    {
        $paymentGateways = PaymentGateway::where('shop_id', $shop->id)->where('name', $payment_method)
                            ->where('is_active', 1)
                            ->first();
        $config = json_decode($paymentGateways->config, true);


        return $config;
    }

    public function razorpay(Request $request)
    {
        $payment = $request->query('payment');
        $saleId = $request->query('sale_id');
        $userId = $request->query('user_id');
        $sales = SaleRepository::find($saleId);
        $amount = $sales->grand_total;

        $data['amount'] = $amount;
        $data['saleId'] = $saleId;
        $data['user_id'] = $userId;

        return view('razorpayApiForm', $data);
    }


    public function razorpayOrder(Request $request)
    {
        $saleId = $request->sale_id;
        $userId = $request->user_id;
        $amount = $request->amount;

        $successUrl = route('payment.success');
        $cancelUrl  = route('payment.cancel');

        $shop = Shop::where('user_id', $userId)->first();
        $gatewayConfigs = $this->getPaymentGatewayConfigs($shop, 'razorpay');

        $razorpayKeyId     = $gatewayConfigs['key'];
        $razorpaySecretKey = $gatewayConfigs['secret'];

        $api = new \Razorpay\Api\Api($razorpayKeyId, $razorpaySecretKey);

        $callbackUrl = $successUrl . '?' . http_build_query([
            'payment' => 'razorpay',
            'sale_id' => $saleId,
            'user'    => $userId,
        ]);


        $orderData = [
            'receipt'         => uniqid(),
            'amount'          => $amount * 100,
            'currency'        => 'INR',
            'payment_capture' => 1,
        ];

        try {
            $order = $api->order->create($orderData);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Error creating Razorpay order: ' . $e->getMessage()
            ], 400);
        }

        return response()->json([
            'status'     => 'success',
            'data'       => [
                'key'         => $razorpayKeyId,
                'order_id'    => $order->id,
                'amount'      => $amount * 100,
                'currency'    => 'INR',
                'name'        => 'Your Company Name',
                'description' => 'Payment for Subscription',
                'prefill'     => [
                    'name'  => 'Rojoni',
                    'email' => 'hjg@gmail.com',
                ],
                'theme'       => [
                    'color' => '#F37254',
                ],
                'callback_url'=> $callbackUrl,
            ],
        ]);
    }


    public function success(Request $request, $id=null)
    {
        if($id != null){
            return $this->json('Successfully Payment Complete', [
                'invoice_pdf_url' => $this->downloadInvoice($id),
            ]);
        }else{
            return 'success';
        }

    }

    public function invoice($id=null)
    {
        $message = 'Successfully Payment Complete';
        return $this->json($message, [
            'invoice_pdf_url' => $this->downloadInvoice($id),
        ]);

    }


    public function cancelPayment()
    {
        return $this->json('Something went wrong ', []);

    }


}
