<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use App\Traits\ShopTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpenseRepository extends Repository
{
    use ShopTrait;
    
    public static function model()
    {
        return Expense::class;
    }
    public static function storeByRequest(ExpenseRequest $request, $accountId = null, $paymentType = null)
    {
        $create = self::create([
            'created_by' => auth()->id(),
            'shop_id' => self::mainShop()->id,
            'reference_no' => 'er-' . date("Ymd") . '-' . date("his"),
            'expense_category_id' => $request->expense_category_id,
            'warehouse_id' => $request->warehouse_id,
            'amount' => $request->amount,
            'account_id' => $accountId,
            'payment_type' => $paymentType ?? $request->payment_type,
            'note' => $request->note,
        ]);
        return $create;
    }

    public static function updateByRequest(ExpenseRequest $request, Expense $expense)
    {
        $update = self::update($expense, [
            'expense_category_id' => $request->expense_category_id,
            'warehouse_id' => $request->warehouse_id,
            'payment_type' => $request->payment_type,
            'note' => $request->note,
        ]);
        return $update;
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
