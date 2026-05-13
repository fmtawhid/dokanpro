<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Repositories\AccountRepository;

class AccountsController extends Controller
{
    public function index()
    {
        $accounts = AccountRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        return view('account.index', compact('accounts'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'account_no' => 'required|numeric|unique:accounts,account_no',
            'name' => 'required|string',
            'balance' => 'required|numeric',
        ]);

        AccountRepository::storeByRequest($request);
        return back()->with('success', 'Account is created successfully');
    }

    public function update(Request $request, Account $account)
    {
        $request->validate([
            'account_no' => 'required|numeric',
            'name' => 'required|string|max:255',
        ]);

        AccountRepository::updateByRequest($request, $account);
        return back()->with('success', 'Account is updated successfully');
    }

    public function updateBalance(Request $request, Account $account)
    {
        $request->validate([
            'total_balance' => 'required|numeric',
        ]);
        AccountRepository::addBalance($request, $account);
        return back()->with('success', 'Balance added successfully');
    }

    public function balanceSheet()
    {
        $accounts = AccountRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        return view('account.balance_sheet', compact('accounts'));
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return back()->with('success', 'Account is deleted successfully');
    }
}
