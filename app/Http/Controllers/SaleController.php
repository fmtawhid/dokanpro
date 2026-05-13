<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SaleResource;
use App\Repositories\BrandRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CustomerGroupRepository;
use App\Repositories\GeneralSettingRepository;
use App\Repositories\ProductRepository;
use App\PaymentMethod\OrangeMoney;
use App\Repositories\PurchaseProductSerialNumberRepository;
use App\Repositories\SaleRepository;
use App\Services\PayfastService;
use App\Models\PaymentGateway;
use App\Services\StripePayService;
use App\Services\PaypalService;
use App\Services\RazorPayService;
use App\Services\PaystackService;
use App\Services\OrangePayService;
use App\Models\Sale;
use App\Models\Shop;

class SaleController extends Controller
{
    public function __construct(
        protected StripePayService $stripeService,
        protected RazorPayService $razorpayService,
        protected PaypalService $paypalService,
        protected PaystackService $paystackService,
        protected PayfastService $payfastService,
        protected OrangePayService $orangepayService,

    ) {}

    public function index()
    {
        $sales = SaleRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->where('type', 'Sales')->get();
        return view('sale.index', compact('sales'));
    }
    public function posSale()
    {
        $shop = $this->mainShop();
        
        if (!$shop) {
            abort(404, 'Shop not found');
        }
        
        $stripeGateway = PaymentGateway::where('name', 'stripe')->where('shop_id', $shop->id)->first();
        $razorpayGateway = PaymentGateway::where('name', 'razorpay')->where('shop_id', $shop->id)->first();
        $payStackGateway = PaymentGateway::where('name', 'paystack')->where('shop_id', $shop->id)->first();
        $payPalGateway = PaymentGateway::where('name', 'paypal')->where('shop_id', $shop->id)->first();
        $payfastGateway = PaymentGateway::where('name', 'payfast')->where('shop_id', $shop->id)->first();
        $data['sales'] = SaleRepository::find(request()->id);
        $data['paymentGateways'] = PaymentGateway::where('shop_id', $shop->id)->where('is_active', 1) ->get();
        if($stripeGateway != null):
            $data['stripe_publish_key'] = optional(json_decode($stripeGateway->config))->published_key ?? '';
            $data['stripe_secret_key'] = optional(json_decode($stripeGateway->config))->secret_key ?? '';

        endif;
        if($razorpayGateway != null):
            $data['razorpay_publish_key'] = optional(json_decode($razorpayGateway->config))->key ?? '';
        endif;
        if($payStackGateway != null):
            $data['paystack_publish_key'] = optional(json_decode($payStackGateway->config))->public_key ?? '';
        endif;
        if($payPalGateway != null):
            $data['paypal_client_id'] = optional(json_decode($payPalGateway->config))->client_id ?? '';
        endif;
        if($payfastGateway != null):
            $data['payfast_client_id'] = optional(json_decode($payfastGateway->config))->merchant_id ?? '';
            $data['payfast_client_secret'] = optional(json_decode($payfastGateway->config))->merchant_key ?? '';
        endif;

        return view('sale.pos.index', $data);
    }
    public function generateInvoice($id)
    {
        $sale = SaleRepository::find($id);
        $generalsettings = GeneralSettingRepository::query()->where('shop_id', $this->mainShop()->id)->first();
        return view('sale.invoice', compact('sale', 'generalsettings'));
    }
    public function draft()
    {
        $drafts = SaleRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->where('type', 'Draft')->get();
        return view('sale.draft', compact('drafts'));
    }
    public function draftDelete(Sale $sale)
    {
        foreach ($sale->productSales as $draftProduct) {
            $draftProduct->product->update(['qty' => $draftProduct->product->qty + $draftProduct->qty]);
        }
        $sale->productSales()->delete();
        $sale->delete();
        return back()->with('success', 'Draft successfully deleted');
    }
    public function posData()
    {
        $categories = CategoryRepository::query()->where('shop_id', $this->mainShop()->id)->get();
        $brandes = BrandRepository::query()->where('shop_id', $this->mainShop()->id)->get();
        $featuredProducts = ProductRepository::query()->where('shop_id', $this->mainShop()->id)->whereNotNull('is_featured')->get();
        $generalSetting = GeneralSettingRepository::query()->where('shop_id', $this->mainShop()->id)->first();
        $customerGroups = CustomerGroupRepository::query()->where('shop_id', $this->mainShop()->id)->get();
        $barcodeDigits = $generalSetting->barcode_digits ?? 8;

        return $this->json('Pos data', [
            'categories' => CategoryResource::collection($categories),
            'brands' => BrandResource::collection($brandes),
            'featuredProducts' => ProductResource::collection($featuredProducts),
            'barcodeDigits' => $barcodeDigits,
            'customerGroups' => $customerGroups,
        ]);
    }
    public function sale(SaleRequest $request)
    {

        $products = ProductRepository::query()->whereIn('id', $request->product_ids)->get();
        if (feature('purchases')) {
            foreach ($products as $key => $product) {
                if ($product->qty < $request->qty[$key]) {
                    return $this->json($product->name . 'this product quantity less than your request', [], 422);
                }
            }
        }
        if ($request->sale_id) {
            $draftSales = SaleRepository::find($request->sale_id);
            foreach ($draftSales->productSales as $draftProduct) {
                $draftProduct->product->update(['qty' => $draftProduct->product->qty + $draftProduct->qty]);
            }
            $draftSales->productSales()->delete();
            $draftSales->delete();
        }
        if($request->payment_method != 'cash' && $request->payment_method != '' && $request->payment_method != 'orangepay'):
            $shop = $this->mainShop();
            $paymentGateway = PaymentGateway::where('name', $request->payment_method)->where('shop_id', $shop->id)->first();
            $config = json_decode($paymentGateway->config);
            $this->{$request->payment_method . 'Service'}->paymentProcess($request, $config);
        endif;
        if($request->payment_method == 'orangepay'):
            $response = $this->orangeMoneyPaymentprocess($request);
            if($response == false):
                return $this->json('payment is not completed', [], 422);
            endif;
        endif;
        if ($request->product_serial_numbers) {
            PurchaseProductSerialNumberRepository::query()->whereIn('id', $request->product_serial_numbers)->update(['selling_status' => 1]);
        }

        $sale = SaleRepository::storeByRequest($request);
        $message = 'Product successfull sold';
        if ($request->type == 'Draft') {
            $message = 'Product successfull drafted';
        }
        return $this->json($message, [
            'sale' => SaleResource::make($sale),
        ]);
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

    public function orangeMoneyPaymentprocess($request)
    {
        $request->validate([
            'pin_code' => 'required',
            'phone_number' => 'required'
        ]);
        $shop = $this->mainShop();
        $paymentGateway = PaymentGateway::where('name', $request->payment_method)->where('shop_id', $shop->id)->first();
        $orangePay = json_decode($paymentGateway->config);
        if (!$orangePay) {
            return back()->withError('Orange money payment gateway not configured please contact to the admin');
        }
        $orangeMoney = new OrangeMoney($orangePay->client_id, $orangePay->client_secret);
        $responseOtp = $orangeMoney->getOtp($request>pin_code, $request->phone_number, $orangePay);

        if (!isset($responseOtp['otp'])) {
            return back()->withError('Invalid pin code or phone number for orange money, please check and try again');
        }
        $response = $orangeMoney->makePayment($orangePay->merchant_code, $request->phone_number, $responseOtp['otp'], $request->paid_amount, $orangePay);
        if ($response['status'] == 'SUCCESS') {
            return true;
        } else {
            return false;
        }
    }
}
