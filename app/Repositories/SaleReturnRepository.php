<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\SaleReturnRequest;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Traits\ShopTrait;
use Carbon\Carbon;

class SaleReturnRepository extends Repository
{
    use ShopTrait;
    
    public static function model()
    {
        return SaleReturn::class;
    }
    public static function storeByRequest(SaleReturnRequest $request, Sale $sale)
    {
        $referenceNo = 'rrp-' . date("Ymd") . '-' . date("his");
        return self::create([
            'created_by' => auth()->id(),
            'shop_id' => self::mainShop()->id,
            'reference_no' => $referenceNo,
            'total_discount' => $sale->total_discount - $request->total_discount,
            'total_tax' => $sale->total_tax - $request->total_tax,
            'total_qty' => $sale->total_qty - $request->total_qty,
            'item' => $sale->item - $request->item,
            'total_price' => $sale->total_price - $request->total_price,
            'order_tax' => $sale->order_tax - $request->order_tax,
            'grand_total' => $sale->grand_total - $request->grand_total,
            'sale_note' => $request->note,
        ]);
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
