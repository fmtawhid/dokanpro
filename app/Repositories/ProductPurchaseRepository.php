<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\ProductPurchase;
use Carbon\Carbon;

class ProductPurchaseRepository extends Repository
{
    public static function model()
    {
        return ProductPurchase::class;
    }

    public static function storeByRequet(array $product, $purchase)
    {
        return self::create([
            'product_id' => $product['id'],
            'purchase_id' => $purchase->id,
            'qty' => $product['qty'],
            'purchase_unit_id' => 1,
            'net_unit_cost' => $product['netUnitCost'],
            'discount' => $product['discount'] ?? 0,
            'tax_rate' => $product['textRate'],
            'tax' => $product['tax'],
            'total' => $product['subTotal'],
        ]);
    }

    public static function filterByRecurringType($purchases, $start_date, $end_date, $type, $hasDate)
    {
        return self::query()->whereIn('purchase_id', $purchases->pluck('id'))
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
            })
            ->selectRaw('SUM(qty) as total_quantity, product_id')
            ->whereMonth('created_at', date('m'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();
    }

    public static function filterByMonth($purchases, $startDate, $endDate)
    {
        return self::query()->whereIn('purchase_id', $purchases->pluck('id'))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month')
            ->selectRaw('COALESCE(SUM(total), 0) as total_sales')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }
}
