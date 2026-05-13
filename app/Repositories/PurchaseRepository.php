<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\PurchaseRequest;
use App\Models\Media;
use App\Models\Purchase;
use App\Traits\ShopTrait;
use Carbon\Carbon;

class PurchaseRepository extends Repository
{
    use ShopTrait;

    public static $path = 'purchase';
    public static function model()
    {
        return Purchase::class;
    }

    public static function getCurrentMonthPurchase($shopId)
    {
        return self::query()->where('shop_id', $shopId)->whereMonth('created_at', date('m'))->get();
    }

    public static function storeByRequest(PurchaseRequest $request)
    {
        $documentId = null;
        if ($request->hasFile('document')) {
            $document = MediaRepository::storeByRequest($request->document, self::$path, 'Image');
            $documentId = $document->id;
        }
        $amount = $request->paid_amount ?? 0;
        $payment_status = $request->grand_total == $amount ? true : false;
        $date = $request->date ? $request->date : now()->format('Y-m-d');

        $purchase = self::create([
            'created_by' => auth()->id(),
            'shop_id' => self::mainShop()->id,
            'reference_no' => 'pr-' . date("Ymd") . '-' . date("his"),
            'user_id' => auth()->id(),
            'warehouse_id' => $request->warehouse_id,
            'supplier_id' => $request->supplier_id,
            'tax_id' => $request->tax_id,
            'item' => $request->item,
            'total_qty' => $request->total_qty,
            'total_discount' => $request->order_discount ?? 0,
            'total_tax' => $request->total_tax,
            'total_cost' => $request->total_cost,
            'order_tax_rate' => $request->order_tax_rate,
            'order_tax' => $request->order_tax,
            'order_discount' => $request->order_discount,
            'shipping_cost' => $request->shipping_cost,
            'grand_total' => round($request->grand_total),
            'paid_amount' => $amount,
            'status' => $request->status,
            'payment_status' => $payment_status,
            'payment_method' => $request->payment_method,
            'document_id' => $documentId,
            'note' => $request->note,
            'date' => $date,
        ]);

        if ($request->paid_amount) {
            $request['purpose'] = "Purchased " . $request->item . " new products";
            $request['amount'] = $amount;
            if ($request->payment_method == 'Bank') {
                TransactionRepository::storeByRequestForBank($request, $request->account_id, false);
                PaymentRepository::storeByRequest($request, $purchase->id);
            } else {
                TransactionRepository::storeByRequestForCash($request, 'Debit');
            }
        }

        return $purchase;
    }

    public static function updateByRequest(PurchaseRequest $request, Purchase $purchase)
    {
        $document = self::documentUpdateOrCreateByRequest($request, $purchase->document);
        $amount = $request->paid_amount ?? 0;
        $payment_status = $request->grand_total == $amount ? true : false;
        $date = $request->date ? $request->date : now()->format('Y-m-d');

        $updatePurchase = self::update($purchase, [
            'warehouse_id' => $request->warehouse_id,
            'supplier_id' => $request->supplier_id,
            'tax_id' => $request->tax_id,
            'item' => $request->item,
            'total_qty' => $request->total_qty,
            'total_discount' => $request->order_discount ?? 0,
            'total_tax' => $request->total_tax,
            'total_cost' => $request->total_cost,
            'order_tax_rate' => $request->order_tax_rate,
            'order_tax' => $request->order_tax,
            'order_discount' => $request->order_discount,
            'shipping_cost' => $request->shipping_cost,
            'grand_total' => round($request->grand_total),
            'paid_amount' => $amount,
            'status' => $request->status,
            'payment_status' => $payment_status,
            'payment_method' => $request->payment_method,
            'document_id' => $document->id ?? null,
            'note' => $request->note,
            'date' => $date,
        ]);

        if ($request->paid_amount) {
            $request['purpose'] = "Purchased " . $request->item . " new products";
            $request['amount'] = $amount;
            if ($request->payment_method == 'Bank') {
                TransactionRepository::storeByRequestForBank($request, $request->account_id, false);
                PaymentRepository::storeByRequest($request, $purchase->id);
            } else {
                TransactionRepository::storeByRequestForCash($request, 'Debit');
            }
        }

        return $updatePurchase;
    }

    public static function documentUpdateOrCreateByRequest(PurchaseRequest $request, Media $media = null)
    {
        if ($media) {
            if ($request->hasFile('document')) {
                return MediaRepository::updateByRequest(
                    $request->document,
                    self::$path,
                    'Image',
                    $media
                );
            }
        }
        if ($request->hasFile('document')) {
            return MediaRepository::storeByRequest(
                $request->document,
                self::$path,
                'Image'
            );
        }
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
