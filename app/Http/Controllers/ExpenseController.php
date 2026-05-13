<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Repositories\AccountRepository;
use App\Repositories\ExpenseCategoryRepository;
use App\Repositories\ExpenseRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\WarehouseRepository;

class ExpenseController extends Controller
{
    public function index()
    {
        $request = request();
        $hasDate = false;
        $startDate = date('m/d/Y');
        $endDate = date('m/d/Y', strtotime('+8 days'));
        if ($request->start_date && $request->end_date) {
            $startMil = $request->start_date;
            $startSeconds = $startMil / 1000;
            $startDate = date("m/d/Y", $startSeconds);
            $endMil = $request->end_date;
            $endSeconds = $endMil / 1000;
            $endDate = date("m/d/Y", $endSeconds);
            $hasDate = true;
        }
        $expenses = ExpenseRepository::query()->where('shop_id', $this->mainShop()->id)->when($hasDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })->get();

        $warehouses =  WarehouseRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        $exCategories = ExpenseCategoryRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        $accounts = AccountRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        $paymentTypes = ['cash' => 'Cash', 'bank' => 'Bank'];
        
        return view('expense.index', compact('expenses', 'warehouses', 'exCategories', 'accounts', 'paymentTypes', 'startDate', 'endDate'));
    }

    public function store(ExpenseRequest $request)
    {
        $accountId = null;
        
        if ($request->payment_type === 'bank') {
            $accountId = $request->account_id;
            $account = AccountRepository::query()->where('id', $accountId)->first();
            
            if (!$account) {
                return back()->with('error', 'Account not found!');
            }
            
            if ($request->amount > $account->total_balance) {
                return back()->with('error', 'Your account does not have enough money!');
            }
            
            TransactionRepository::debitByRequest('Bank', $request->amount, $accountId, 'Expense');
            $account->update(['total_balance' => $account->total_balance - $request->amount]);
        }
        
        ExpenseRepository::storeByRequest($request, $accountId, $request->payment_type);
        return back()->with('success', 'Data is inserted successfully');
    }

    public function update(ExpenseRequest $request, Expense $expense)
    {
        ExpenseRepository::updateByRequest($request, $expense);
        return back()->with('success', 'Data is updated successfully');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return back()->with('success', 'Data is deleted successfully');
    }
}
