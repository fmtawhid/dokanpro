<?php

namespace App\Http\Controllers;

use App\Repositories\ExpenseRepository;
use App\Repositories\GeneralSettingRepository;
use App\Repositories\PayrollRepository;
use App\Repositories\ProductPurchaseRepository;
use App\Repositories\ProductSaleRepository;
use App\Repositories\PurchaseRepository;
use App\Repositories\SaleRepository;
use App\Repositories\SaleReturnRepository;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $request = request();
        $type = $request->type;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $hasDate = $start_date && $end_date ? true : false;

        $saleReturns = SaleReturnRepository::filterByRecurringType($start_date, $end_date, $type, $hasDate);

        $payrolls = PayrollRepository::filterByRecurringType($start_date, $end_date, $type, $hasDate);

        $expenses = ExpenseRepository::filterByRecurringType($start_date, $end_date, $type, $hasDate);

        $sales = SaleRepository::filterByRecurringType($start_date, $end_date, $type, $hasDate);

        $purchases = PurchaseRepository::filterByRecurringType($start_date, $end_date, $type, $hasDate);

        $productPurchases = ProductPurchaseRepository::filterByRecurringType($purchases, $start_date, $end_date, $type, $hasDate);

        $monthlyTotalProductSales = ProductSaleRepository::filterByRecurringType($sales, $start_date, $end_date, $type, $hasDate);

        $totalProductSales = $monthlyTotalProductSales->get();

        $productSales = $monthlyTotalProductSales
            ->selectRaw('SUM(qty) as total_quantity, product_id')
            ->whereMonth('created_at', date('m'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $perMonthSales = ProductSaleRepository::filterByMonth($sales, $startDate, $endDate);

        $perMonthPurchases = ProductPurchaseRepository::filterByMonth($purchases, $startDate, $endDate);

        $perMonthDabits = TransactionRepository::filterByMonth($startDate, $endDate, 'Debit');

        $perMonthCredits = TransactionRepository::filterByMonth($startDate, $endDate, 'Credit');

        $transactions = TransactionRepository::query()->where('shop_id', $this->mainShop()->id)->latest()->take(5)->get();

        $formattedSales = [];
        $formattedPurchases = [];
        $formattedDabits = [];
        $formattedCradits = [];

        $currentDate = $startDate;
        while ($currentDate <= $endDate) {
            $formattedDate = $currentDate->format('Y-m');
            $foundDataforSale = $perMonthSales->firstWhere('month', $formattedDate);
            $foundDataforPurchase = $perMonthPurchases->firstWhere('month', $formattedDate);
            $foundDataforDabit = $perMonthDabits->firstWhere('month', $formattedDate);
            $foundDataforCredit = $perMonthCredits->firstWhere('month', $formattedDate);

            $formattedSales[] = $foundDataforSale ? (int) $foundDataforSale->total_sales : 0;
            $formattedPurchases[] = $foundDataforPurchase ? (int) $foundDataforPurchase->total_sales : 0;
            $formattedDabits[] = $foundDataforDabit ? (int) $foundDataforDabit->total_amount : 0;
            $formattedCradits[] = $foundDataforCredit ? (int) $foundDataforCredit->total_amount : 0;

            $currentDate->addMonth();
        }

        $monthlyNetProfit = 0;
        $monthlyGrossProfit = 0;

        $purchasesTotalQty = $purchases->sum('total_qty');
        $extraCost = ($purchasesTotalQty == 0) ? 0 : $expenses->sum('amount') / $purchasesTotalQty;

        foreach ($totalProductSales as $monthlyTotalProductSale) {
            $monthlyNetProfit += ($monthlyTotalProductSale->product->price - $monthlyTotalProductSale->product->cost) * $monthlyTotalProductSale->qty;
            $monthlyGrossProfit += ($monthlyTotalProductSale->product->price - ($monthlyTotalProductSale->product->cost + $extraCost)) * $monthlyTotalProductSale->qty;
        }
        $themeColor = GeneralSettingRepository::query()->where('shop_id', $this->mainShop()->id)->first()?->dark_mode ? 'dark' : 'light';

        return view('dashboard.index', compact('purchases', 'monthlyNetProfit', 'monthlyGrossProfit', 'saleReturns', 'payrolls', 'sales', 'transactions', 'expenses', 'productPurchases', 'productSales', 'formattedSales', 'formattedPurchases', 'formattedDabits', 'formattedCradits', 'expenses', 'themeColor'));
    }
}
