<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\SaleRequest;
use App\Models\ProductSale;
use Carbon\Carbon;

class ProductSaleRepository extends Repository
{
    public static function model()
    {
        return ProductSale::class;
    }

    public static function getMonthlyTotalProductSales($shopID)
    {
        $SaleIds = SaleRepository::query()->where('shop_id', $shopID)->whereMonth('created_at', date('m'))->pluck('id')->toArray();

        return self::query()->whereIn('sale_id', $SaleIds)->whereMonth('created_at', date('m'))->get();
    }

    public static function storeByRequest(SaleRequest $request, $sale)
    {
        $products = ProductRepository::query()->whereIn('id', $request->product_ids)->get();
        foreach ($products as $key => $product) {
            $productTax = 0;
            $price = isset($request->price[$key]) ? $request->price[$key] : $product->price;

            if ($product->tax) {
                $productTax = $price * $product->tax->rate / 100;
            }

            self::create([
                'variant_id' => $request->product_variant_ids[$key] ?? null,
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'net_unit_price' => $price,
                'qty' => $request->qty[$key],
                'discount' => 0,
                'tax_rate' => $product->tax?->rate,
                'tax' => $productTax,
                'total' => ($price + $productTax) * $request->qty[$key],
            ]);
        }

        return $sale;
    }

    public static function filterByRecurringType($sales, $start_date, $end_date, $type, $hasDate)
    {
        return self::query()->whereIn('sale_id', $sales->pluck('id'))
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
            });
    }

    public static function filterByMonth($sales, $startDate, $endDate)
    {
        return self::query()->whereIn('sale_id', $sales->pluck('id'))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month')
            ->selectRaw('COALESCE(SUM(total), 0) as total_sales')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }
}
