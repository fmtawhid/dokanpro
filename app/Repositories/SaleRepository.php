<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\SaleRequest;
use App\Models\Sale;
use App\Traits\ShopTrait;
use Carbon\Carbon;

class SaleRepository extends Repository
{
    use ShopTrait;
    private static $path = '/sale';

    public static function model()
    {
        return Sale::class;
    }

    public static function getCurrentMonthSale($shopId)
    {
        return self::query()->where('shop_id', $shopId)->whereMonth('created_at', date('m'))->get();
    }

    public static function storeByRequest(SaleRequest $request)
    {

        $documentId = null;
        if ($request->hasFile('document')) {
            $document = MediaRepository::storeByRequest(
                $request->document,
                self::$path,
                'Image',
            );
            $documentId = $document->id;
        }
        $request['payment_status'] = 3;
        $products = ProductRepository::query()->whereIn('id', $request->product_ids)->get();
        $tax = TaxRepository::find($request->tax_id);
        $coupon = CouponRepository::find($request->coupon_id);

        $totalProductDiscount = 0;
        $totalproductTax = 0;
        $totalPrice = 0;

        foreach ($products as $key => $product) {
            $price = isset($request->price[$key]) ? $request->price[$key] : $product->price;

            if (feature('purchases')) {
                $product->update([
                    'qty' => $product->qty - $request->qty[$key]
                ]);

                $isBatch = $product->whereNotNull('is_batch')->first();
                if ($isBatch) {
                    PurchaseBatchRepository::batchProductSale($isBatch, $request->qty[$key]);
                }
            }

            $productTax = ($price * ($product?->tax->rate ?? 0)) / 100;

            $totalPrice += ($price + $productTax) * $request->qty[$key];
            $totalproductTax += $productTax * $request->qty[$key];
        }

        $referenceNo = 'posr-' . date("Ymd") . '-' . date("his");

        if ($request->reference_no) {
            $referenceNo = $request->reference_no;
        }

        $totalDiscount = $request->discount ?? 0;

        if ($request->discount_type == 'Percentage') {
            $totalDiscount = $totalPrice * $request->discount / 100;
        }

        $totalTax = 0;

        if ($tax) {
            $totalTax = ($totalPrice - $totalDiscount) * $tax->rate / 100;
        }

        $totalCouponAmount = $coupon->amount ?? 0;
        if (isset($coupon->type) && $coupon->type->value == 'Percentage') {
            $totalCouponAmount = $totalPrice * $coupon->amount / 100;
        }

        $grandTotal = ($totalPrice - $totalDiscount - $totalCouponAmount) + $totalTax + $request->shipping_cost;

        $user = auth()->user();

        $sale = self::create([
            'created_by' => auth()->id(),
            'shop_id' => self::mainShop()->id,
            'reference_no' => $referenceNo,
            'customer_id' => $request->customer_id,
            'item' => collect($request->product_ids)->count(),
            'total_qty' => collect($request->qty)->count(),
            'total_discount' => $totalProductDiscount,
            'total_tax' => $totalproductTax,
            'total_price' => $totalPrice,
            'grand_total' => $grandTotal,
            'order_tax_rate' => $tax?->rate,
            'order_tax' => $totalTax,
            'order_discount' => $totalDiscount,
            'coupon_id' => $coupon?->id,
            'coupon_discount' => $totalCouponAmount,
            'shipping_cost' => $request->shipping_cost,
            'sale_status' => 1,
            'payment_status' => $request->payment_statuss  ? $request->payment_statuss :$request->payment_status,
            'payment_method' => $request->payment_method ?? 'Cash',
            'document_id' => $documentId,
            'paid_amount' => $request->paid_amount,
            'sale_note' => $request->sale_note,
            'staff_note' => $request->staff_note,
            'type' => $request->type,
        ]);

        ProductSaleRepository::storeByRequest($request, $sale);
        if ($request->draft_id) {
            $draft = self::find($request->draft_id);
            if (feature('purchases')) {
                foreach ($draft->productSales as $draftProduct) {
                    $draftProduct->product->update(['qty' => $draftProduct->product->qty + $draftProduct->qty]);
                }
            }

            $draft->productSales()->delete();
            $draft->delete();
        }
        $wallet = $user->wallet ??  WalletRepository::store($user);
        WalletRepository::credit($wallet, $grandTotal);
        return $sale;
    }

    public static function filterByRecurringType($start_date, $end_date, $type, $hasDate)
    {
        return self::query()->where('shop_id', self::mainShop()->id)
            ->when($hasDate, function ($query) use ($start_date, $end_date) {
                return $query->whereBetween('created_at', [$start_date, $end_date]);
            })
            ->when($type == 'daily', function ($query) {
                return $query->whereDate('created_at', today());
            })
            ->when($type == 'weekly', function ($query) {
                return $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            })
            ->when($type == 'monthly', function ($query) {
                return $query->whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', Carbon::now()->month);
            })
            ->when($type == 'yearly', function ($query) {
                return $query->whereYear('created_at', Carbon::now()->year);
            })->get();
    }

}
