<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Http\Requests\PurchaseRequest;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Payment;
use App\Repositories\AccountRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\ProductPurchaseRepository;
use App\Repositories\ProductRepository;
use App\Repositories\PurchaseBatchRepository;
use App\Repositories\PurchaseProductSerialNumberRepository;
use App\Repositories\PurchaseRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\TaxRepository;
use App\Repositories\WarehouseRepository;

class PurchaseController extends Controller
{
    public function index()
    {
        $request = request();
        $warehouseId = $request->warehouse_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $hasDate = $startDate && $endDate ? true : false;

        $purchases = PurchaseRepository::query()->orderByDesc('id')->where('shop_id', $this->mainShop()->id)
            ->when($warehouseId, function ($query) use ($warehouseId) {
                $query->where('warehouse_id', $warehouseId);
            })
            ->when($hasDate, function ($query) use ($startDate, $endDate) {
                $query->wherebetween('date', [$startDate, $endDate]);
            })->get();

        $paymentMethods = PaymentMethod::cases();
        $warehouses = WarehouseRepository::query()->orderByDesc('id')->where('shop_id', $this->mainShop()->id)->get();
        $accounts = AccountRepository::query()->orderByDesc('id')->where('shop_id', $this->mainShop()->id)->get();
        return view('purchase.index', compact('accounts', 'warehouses', 'purchases', 'paymentMethods'));
    }

    public function create()
    {
        $suppliers = SupplierRepository::query()->orderByDesc('id')->where('shop_id', $this->mainShop()->id)->get();
        $warehouses = WarehouseRepository::query()->orderByDesc('id')->where('shop_id', $this->mainShop()->id)->get();
        $taxs = TaxRepository::query()->orderByDesc('id')->where('shop_id', $this->mainShop()->id)->get();
        $accounts = AccountRepository::query()->orderByDesc('id')->where('shop_id', $this->mainShop()->id)->get();
        $paymentMethods = PaymentMethod::cases();

        return view('purchase.create', compact('accounts', 'suppliers', 'warehouses', 'taxs', 'paymentMethods'));
    }

    public function store(PurchaseRequest $request)
    {
        if ($request->grand_total < $request->paid_amount) {
            return back()->with('error', 'Your paid amount is greater than your total amount!');
        }
        if ($request->payment_method == 'Bank') {
            $account = AccountRepository::query()->where('id', $request->account_id)->first();

            if ($request->paid_amount > $account->total_balance || $request->grand_total > $account->total_balance) {
                return back()->with('error', 'Your account does not have enough money!');
            }
        }

        $purchase = PurchaseRepository::storeByRequest($request);

        foreach ($request->products as $product) {
            $findProduct = ProductRepository::find($product['id']);
            $findProduct->update(['qty' => ($findProduct->qty + $product['qty'])]);
            $product['textRate'] = $request->order_tax_rate;
            ProductPurchaseRepository::storeByRequet($product, $purchase);
            $batchProducts = ProductRepository::query()->where('id', $product['id'])->whereNotNull('is_batch')->first();
            if ($batchProducts && $product['batch'] && $product['expire_date']) {
                PurchaseBatchRepository::storeByRequest($product, $purchase, $request->date);
            }
            if (isset($product['serial_or_imei_number']) && count($product['serial_or_imei_number']) > 0) {
                foreach ($product['serial_or_imei_number'] as $serial) {
                    PurchaseProductSerialNumberRepository::storeUpByRequest($purchase, $findProduct, $serial);
                }
            }
        }

        return back()->with('success', 'Purchase is created successfully');
    }

    public function edit(Purchase $purchase)
    {
        $suppliers = SupplierRepository::query()->orderByDesc('id')->where('shop_id', $this->mainShop()->id)->get();
        $warehouses = WarehouseRepository::query()->orderByDesc('id')->where('shop_id', $this->mainShop()->id)->get();
        $taxs = TaxRepository::query()->orderByDesc('id')->where('shop_id', $this->mainShop()->id)->get();
        $accounts = AccountRepository::query()->orderByDesc('id')->where('shop_id', $this->mainShop()->id)->get();
        $payment_method = $purchase->payments()->latest()->first()?->paying_method->value;
        $paymentMethods = PaymentMethod::cases();
        return view('purchase.edit', compact('warehouses', 'suppliers', 'taxs', 'purchase', 'accounts', 'payment_method', 'paymentMethods'));
    }

    public function update(PurchaseRequest $request, Purchase $purchase)
    {
        if ($request->grand_total < $request->paid_amount) {
            return back()->with('error', 'Your paid amount is greater than your total amount!');
        }
        if ($request->payment_method == 'Bank') {
            $account = AccountRepository::query()->where('id', $request->account_id)->first();

            if ($request->paid_amount > $account->total_balance || $request->grand_total > $account->total_balance) {
                return back()->with('error', 'Your account does not have enough money!');
            }
        }

        PurchaseRepository::updateByRequest($request, $purchase);

        $purchase->purchaseProducts()->delete();
        $purchase->purchaseBatches()->delete();
        $purchase->purchaseProductsSerialNumbers()->delete();

        foreach ($request->products as $product) {
            $findProduct = ProductRepository::find($product['id']);
            $findProduct->update(['qty' => ($findProduct->qty + $product['qty'])]);
            $product['textRate'] = $request->order_tax_rate;
            ProductPurchaseRepository::storeByRequet($product, $purchase);
            $batchProducts = ProductRepository::query()->where('id', $product['id'])->whereNotNull('is_batch')->first();
            if ($batchProducts && $product['batch'] && $product['expire_date']) {
                PurchaseBatchRepository::storeByRequest($product, $purchase, $request->date);
            }
            if (isset($product['serial_or_imei_number']) && count($product['serial_or_imei_number']) > 0) {
                foreach ($product['serial_or_imei_number'] as $serial) {
                    PurchaseProductSerialNumberRepository::storeUpByRequest($purchase, $findProduct, $serial);
                }
            }
        }

        return back()->with('success', 'Purchase is updated successfully');
    }

    public function addPayment(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required',
            'paid_amount' => 'required',
            'account_id' => $request->payment_method == 'Bank' ? 'required|exists:accounts,id' : 'nullable'
        ]);

        $purchase = PurchaseRepository::find($id);
        $payAble = $purchase->grand_total - $purchase->paid_amount;
        if ($payAble < $request->paid_amount) {
            return back()->with('error', 'Your paid amount is greater than your total amount!');
        }

        $account = null;

        if ($request->payment_method == 'Bank') {
            $account = AccountRepository::query()->where('id', $request->account_id)->first();
            if ($request->paid_amount > $account->total_balance) {
                return back()->with('error', 'Your account does not have enough money!');
            }
        }

        PaymentRepository::addPayment($request, $account);

        $request['purpose'] = "Purchased " . $request->item . " new products";
        $request['amount'] = $request->paid_amount;

        if ($request->payment_method == 'Bank') {
            TransactionRepository::storeByRequestForBank($request, $account->id, false);
        } else {
            TransactionRepository::storeByRequestForCash($request, 'Debit');
        }

        $totalPay = ($purchase->paid_amount + $request->paid_amount);
        $purchase->update([
            'paid_amount' => $totalPay,
            'payment_status' => $totalPay == $purchase->grand_total ? true : false,
        ]);

        return back()->with('success', 'Payment is created successfully');
    }

    public function deletePayment(Payment $payment)
    {
        $payment->delete();
        return back()->with('success', 'Payment is deleted successfully!');
    }

    public function destroy(Purchase $purchase)
    {
        $purchaseProducts = ProductPurchaseRepository::query()->where('purchase_id', $purchase->id)->get();
        foreach ($purchaseProducts as $purchaseProduct) {
            $purchaseProduct->delete();
        }

        $payments = PaymentRepository::query()->where('purchase_id', $purchase->id)->get();
        foreach ($payments as $payment) {
            $payment->delete();
        }

        $purchase->delete();
        return back()->with('success', 'Purchase is deleted successfully!');
    }

    public function batch()
    {
        $purchasebatches = PurchaseBatchRepository::getAll();
        return view('purchase.batch', compact('purchasebatches'));
    }
}
