<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Http\Resources\ExpensesResource;
use App\Repositories\ExpenseRepository;
use Carbon\Carbon;

class ExpensesController extends Controller
{
    public function index(SearchRequest $request)
    {
        $warehouseId = $request->warehouse_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $search = $request->search;

        $page = $request->page;
        $perPage = $request->per_page;
        $skip = ($page * $perPage) - $perPage;

        $expenses = ExpenseRepository::query()->where('shop_id', $this->mainShop()->id)
            ->when($warehouseId, function ($query) use ($warehouseId) {
                $query->where('warehouse_id', $warehouseId);
            })
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('created_at', '>=', Carbon::parse($startDate)->format('Y-m-d'));
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('created_at', '<=', Carbon::parse($endDate)->format('Y-m-d'));
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('expenseCategory', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                });
            })->latest();

        $total = $expenses->count();

        $expenses = $expenses->when($page && $perPage, function ($query) use ($skip, $perPage) {
            return $query->skip($skip)->take($perPage);
        })->get();

        return $this->json("sales list", [
            'total' => $total,
            'total_expense_amount' => (float) $expenses->sum('amount'),
            'expenses' => ExpensesResource::collection($expenses),
        ]);
    }
}
