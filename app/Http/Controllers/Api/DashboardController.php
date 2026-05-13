<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ProductPurchaseRepository;
use App\Repositories\ProductSaleRepository;
use App\Repositories\PurchaseRepository;
use App\Repositories\SaleRepository;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $type = request()->type;
        $value = request()->value;
        $date = Carbon::now();
        $parse = null;
        if ($type == 'daily' && $value) {
            $parse = date('Y') . '-' . date('m') . '-' . $value;
        } elseif ($type == 'weekly') {
            $parse = date('Y-m-d');
        } elseif ($type == 'monthly' && $value) {
            $parse = date('Y') . '-' . $value . '-01';
        } elseif ($type == 'yearly' && $value) {
            $parse =  $value . '-' . date('m') . '-01';
        }

        if ($parse) {
            $date = Carbon::parse($parse);
        }

        $sales = SaleRepository::query()->where('shop_id', $this->mainShop()->id)
            ->when($type == 'daily', function ($query) use ($date) {
                return $query->whereDate('created_at', $date->format('Y-m-d'));
            })
            ->when($type == 'weekly', function ($query) use ($date) {
                return $query->whereBetween('created_at', [$date->startOfWeek()->format('Y-m-d'), $date->endOfWeek()->format('Y-m-d')]);
            })
            ->when($type == 'monthly', function ($query) use ($date) {
                return $query->whereBetween('created_at', [$date->startOfMonth()->format('Y-m-d'), $date->endOfMonth()->format('Y-m-d')]);
            })
            ->when($type == 'yearly', function ($query) use ($date) {
                return $query->whereYear('created_at', $date->format('Y'));
            })->get();

        $purchases = PurchaseRepository::query()->where('shop_id', $this->mainShop()->id)
            ->when($type == 'daily', function ($query) use ($date) {
                return $query->whereDate('created_at', $date->format('Y-m-d'));
            })
            ->when($type == 'weekly', function ($query) use ($date) {
                return $query->whereBetween('created_at', [$date->startOfWeek()->format('Y-m-d'), $date->endOfWeek()->format('Y-m-d')]);
            })
            ->when($type == 'monthly', function ($query) use ($date) {
                return $query->whereBetween('created_at', [$date->startOfMonth()->format('Y-m-d'), $date->endOfMonth()->format('Y-m-d')]);
            })
            ->when($type == 'yearly', function ($query) use ($date) {
                return $query->whereYear('created_at', $date->format('Y'));
            })->get();

        $totalProductSales = ProductSaleRepository::query()->whereIn('sale_id', $sales->pluck('id')->toArray())
            ->when($type == 'daily', function ($query) use ($date) {
                return $query->whereDate('created_at', $date->format('Y-m-d'));
            })
            ->when($type == 'weekly', function ($query) use ($date) {
                return $query->whereBetween('created_at', [$date->startOfWeek()->format('Y-m-d'), $date->endOfWeek()->format('Y-m-d')]);
            })
            ->when($type == 'monthly', function ($query) use ($date) {
                return $query->whereBetween('created_at', [$date->startOfMonth()->format('Y-m-d'), $date->endOfMonth()->format('Y-m-d')]);
            })
            ->when($type == 'yearly', function ($query) use ($date) {
                return $query->whereYear('created_at', $date->format('Y'));
            })->get();

        $totalProfit = 0;
        foreach ($totalProductSales as $productSale) {
            $totalProfit += ($productSale->net_unit_price - $productSale->product->cost) * $productSale->qty;
        }

        // Get monthly purchase and sale
        $purchaseAndSales = [];
        $dates = [];
        if ($type == 'daily') {
            for ($i = $date->format('d'); $i >= max($date->format('d') - 5, 1); $i--) {
                $todayDate = Carbon::create(null, $date->format('m'), $i);

                // Check if the day is valid for the current month
                if (!$todayDate->isValid()) {
                    continue; // Skip this iteration if the day is invalid
                }

                $totalPurchase = PurchaseRepository::query()
                    ->where('shop_id', $this->mainShop()->id)
                    ->whereDate('created_at', $todayDate->format('Y-m-d'))->sum('grand_total');
                $totalSale = SaleRepository::query()
                    ->where('shop_id', $this->mainShop()->id)
                    ->whereDate('created_at', $todayDate->format('Y-m-d'))->sum('grand_total');

                $purchaseAndSales[$todayDate->format('d')] = [
                    'purchase' => (float) $totalPurchase,
                    'sale' => (float) $totalSale
                ];

                $dates[$todayDate->format('d')] = $todayDate->format('d');
            }
        } elseif ($type == 'weekly') {
            $startDay = max($date->format('d') - 5, 1);
            for ($i = $date->format('d'); $i >= $startDay; $i--) {
                $todayDate = Carbon::create(null, $date->format('m'), $i);

                // Check if the day is valid for the current month
                if (!$todayDate->isValid()) {
                    continue; // Skip this iteration if the day is invalid
                }

                // Fetch total purchases and sales for the current date
                $totalPurchase = PurchaseRepository::query()
                    ->where('shop_id', $this->mainShop()->id)
                    ->whereDate('created_at', $todayDate->format('Y-m-d'))
                    ->sum('grand_total');

                $totalSale = SaleRepository::query()
                    ->where('shop_id', $this->mainShop()->id)
                    ->whereDate('created_at', $todayDate->format('Y-m-d'))
                    ->sum('grand_total');

                // Store the results
                $purchaseAndSales[$todayDate->format('d')] = [
                    'purchase' => (float) $totalPurchase,
                    'sale' => (float) $totalSale
                ];

                // Store the day name
                $dates[$todayDate->format('d')] = $todayDate->format('l');
            }
        } elseif ($type == 'monthly') {
            $todayDate = $date->format('m') >= 6 ? $date->format('m') : 6;
            for ($i = $todayDate; $i >= 1; $i--) {
                $month = Carbon::create(null, $i, 1);
                $totalPurchase = PurchaseRepository::query()
                    ->where('shop_id', $this->mainShop()->id)
                    ->whereBetween('created_at', [$month->startOfMonth()->format('Y-m-d'), $month->endOfMonth()->format('Y-m-d')])->sum('grand_total');
                $totalSale = SaleRepository::query()
                    ->where('shop_id', $this->mainShop()->id)
                    ->whereBetween('created_at', [$month->startOfMonth()->format('Y-m-d'), $month->endOfMonth()->format('Y-m-d')])->sum('grand_total');

                $purchaseAndSales[$month->format('M')] = [
                    'purchase' => (float) $totalPurchase,
                    'sale' => (float) $totalSale
                ];
                $dates[$month->format('M')] = $month->format('M');
            }
        } elseif ($type == 'yearly') {
            for ($i = $date->format('Y'); $i >= $date->format('Y') - 5; $i--) {
                $totalPurchase = PurchaseRepository::query()
                    ->where('shop_id', $this->mainShop()->id)
                    ->whereYear('created_at', $i)->sum('grand_total');
                $totalSale = SaleRepository::query()
                    ->where('shop_id', $this->mainShop()->id)
                    ->whereYear('created_at', $i)->sum('grand_total');

                $purchaseAndSales[$i] = [
                    'purchase' => (float) $totalPurchase,
                    'sale' => (float) $totalSale
                ];
                $dates[$i] = (string) $i;
            }
        }

        $maxPurchase = max(array_column($purchaseAndSales, 'purchase') ?: [0]);
        $maxSale = max(array_column($purchaseAndSales, 'sale') ?: [0]);
        $maxAmount = max($maxPurchase, $maxSale);


        $customerPie = [
            [
                'name' => 'New Customer',
                'value' => 15
            ],
            [
                'name' => 'New Customer Order',
                'value' => 10
            ],
            [
                'name' => 'Old Customer Order',
                'value' => 45
            ],
            [
                'name' => 'Inactive Customer',
                'value' => 30
            ],
        ];

        $productPurchases = ProductPurchaseRepository::query()->whereIn('purchase_id', $purchases->pluck('id'))
            ->when($type == 'daily', function ($query) use ($date) {
                return $query->whereDate('created_at', $date->format('Y-m-d'));
            })
            ->when($type == 'weekly', function ($query) use ($date) {
                return $query->whereBetween('created_at', [$date->startOfWeek()->format('Y-m-d'), $date->endOfWeek()->format('Y-m-d')]);
            })
            ->when($type == 'monthly', function ($query) use ($date) {
                return $query->whereBetween('created_at', [$date->startOfMonth()->format('Y-m-d'), $date->endOfMonth()->format('Y-m-d')]);
            })
            ->when($type == 'yearly', function ($query) use ($date) {
                return $query->whereYear('created_at', $date->format('Y'));
            })
            ->selectRaw('SUM(qty) as total_quantity, product_id')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'product_name' => $item->product->name,
                    'total_quantity' => $item->total_quantity,
                ];
            });

        $productSales = ProductSaleRepository::query()->whereIn('sale_id', $sales->pluck('id'))
            ->when($type == 'daily', function ($query) use ($date) {
                return $query->whereDate('created_at', $date->format('Y-m-d'));
            })
            ->when($type == 'weekly', function ($query) use ($date) {
                return $query->whereBetween('created_at', [$date->startOfWeek()->format('Y-m-d'), $date->endOfWeek()->format('Y-m-d')]);
            })
            ->when($type == 'monthly', function ($query) use ($date) {
                return $query->whereBetween('created_at', [$date->startOfMonth()->format('Y-m-d'), $date->endOfMonth()->format('Y-m-d')]);
            })
            ->when($type == 'yearly', function ($query) use ($date) {
                return $query->whereYear('created_at', $date->format('Y'));
            })
            ->selectRaw('SUM(qty) as total_quantity, product_id')
            ->whereMonth('created_at', date('m'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'product_name' => $item->product->name,
                    'total_quantity' => $item->total_quantity,
                ];
            });

        return $this->json("Dashboard items", [
            'sale' => (float) $sales->sum('grand_total'),
            'purchase' => (float) $purchases->sum('grand_total'),
            'profit' => (float) $totalProfit,
            'purchase_due' => (float) ($purchases->sum('grand_total') - $purchases->sum('paid_amount')),
            'max_chart_amount' => (int) $maxAmount,
            'purchase_and_sale_chart' => array_values($purchaseAndSales),
            'customer_pie' => $customerPie,
            'product_purchases' => $productPurchases,
            'product_sales' => $productSales,
            'dates' => array_values($dates)
        ]);
    }
}
