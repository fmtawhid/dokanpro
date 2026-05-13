<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\PayrollRequest;
use App\Models\Payroll;
use App\Traits\ShopTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PayrollRepository extends Repository
{
    use ShopTrait;
    
    public static function model()
    {
        return Payroll::class;
    }
    public static function storeByRequest(PayrollRequest $request)
    {
        $create = self::create([
            'created_by' => auth()->id(),
            'shop_id' => self::mainShop()->id,
            'date' => $request->date,
            'employee_id' => $request->employee_id,
            'account_id' => $request->account_id,
            'amount' => $request->amount,
            'note' => $request->note,
        ]);

        return $create;
    }

    public static function updateByRequest(PayrollRequest $request, Payroll $payroll)
    {
        $update = self::update($payroll, [
            'date' => $request->date,
            'employee_id' => $request->employee_id,
            'account_id' => $request->account_id,
            'amount' => $request->amount,
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
