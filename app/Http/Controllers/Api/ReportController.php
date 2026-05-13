<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ProductSaleRepository;
use App\Repositories\PurchaseRepository;
use App\Repositories\SaleRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $shopID = $this->mainShop()->id;

        $purchases = PurchaseRepository::getCurrentMonthPurchase($shopID);

        $sales = SaleRepository::getCurrentMonthSale($shopID);

        $transactions = TransactionRepository::getCurrntMonthCredit($shopID);

        $totalProductSales = ProductSaleRepository::getMonthlyTotalProductSales($shopID);

        $monthlyProfit = 0;

        foreach ($totalProductSales as $productSales) {
            $monthlyProfit += ($productSales->net_unit_price - $productSales->product->cost) * $productSales->qty;
        }

        return $this->json("reports", [
            'purchases' => [
                'total' => (float) $purchases->sum('grand_total'),
                'paid' => (float) $purchases->sum('paid_amount'),
                'tax' => (float) $purchases->sum('total_tax'),
                'discount' => (float) $purchases->sum('total_discount'),
                'due' => (float) ($purchases->sum('grand_total') - $purchases->sum('paid_amount')),
                'products' => (int) $purchases->sum('total_qty')
            ],
            'sales' => [
                'total' => (float) $sales->sum('grand_total'),
                'tax' => (float) $sales->sum('total_tax'),
                'discount' => (float) $sales->sum('total_discount'),
                'selling_product' => (int) $sales->sum('total_qty'),
                'available_product' => (int) ($purchases->sum('total_qty') - $sales->sum('total_qty')),
            ],
            'payment_received' => [
                'total' => (float) $transactions->sum('amount'),
                'cash' => (float) $transactions->where('payment_method', 'Cash')->sum('amount'),
                'bank' => (float) $transactions->where('payment_method', 'Bank')->sum('amount'),
                'cash_count' => (int) $transactions->where('payment_method', 'Cash')->count(),
                'bank_count' => (int) $transactions->where('payment_method', 'Bank')->count(),
            ],
            'profit' => (float) $monthlyProfit
        ]);
    }
}
