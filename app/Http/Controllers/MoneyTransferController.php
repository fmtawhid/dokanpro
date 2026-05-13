<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\MoneyTransfer;
use App\Repositories\AccountRepository;
use App\Repositories\MoneyTransferRepository;
use Illuminate\Http\Request;

class MoneyTransferController extends Controller
{
    public function index()
    {
        $moneyTransfer = MoneyTransferRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        $accounts = AccountRepository::query()->where('shop_id', $this->mainShop()->id)->get();
        return view('moneyTransfer.index', compact('moneyTransfer', 'accounts'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'from_account_id' => 'required|exists:' . (new Account())->getTable() . ',id',
            'to_account_id' => 'required|exists:' . (new Account())->getTable() . ',id',
            'amount' => 'required|numeric',
        ]);

        if ($request->from_account_id == $request->to_account_id) {
            return back()->with('error', 'Please select different account!');
        }

        $formAmount = AccountRepository::find($request->from_account_id);
        if ($request->amount > $formAmount->total_balance) {
            return back()->with('error', 'You have extend your account balance!');
        }
        
        MoneyTransferRepository::storeByRequest($request);
        return back()->with('success', 'Money transfered is successfully');
    }

    public function update(Request $request, MoneyTransfer $moneyTransfer)
    {
        MoneyTransferRepository::updateByRequest($request, $moneyTransfer);
        return back()->with('success', 'Money transfere is updated successfully');
    }

    public function destroy(MoneyTransfer $moneyTransfer)
    {
        $moneyTransfer->delete();
        return back()->with('success', 'Money transfere is deleted successfully');
    }
}
