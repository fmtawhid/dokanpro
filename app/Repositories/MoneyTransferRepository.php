<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\MoneyTransfer;
use App\Traits\ShopTrait;
use Illuminate\Http\Request;

class MoneyTransferRepository extends Repository
{
    use ShopTrait;
    
    public static function model()
    {
        return MoneyTransfer::class;
    }

    public static function storeByRequest(Request $request)
    {
        $fromAccount = AccountRepository::find($request->from_account_id);
        $toAccount = AccountRepository::find($request->to_account_id);

        $debit = $fromAccount->total_balance - $request->amount;
        AccountRepository::balanceUpdate($debit, $fromAccount);
        TransactionRepository::debitByRequest('Bank', $request->amount, $fromAccount->id, 'moneytransfer');

        $credit = $toAccount->total_balance + $request->amount;
        AccountRepository::balanceUpdate($credit, $toAccount);
        TransactionRepository::creditByRequest('Bank', $request->amount, $toAccount->id, 'moneytransfer');

        $create = self::create([
            'created_by' => auth()->id(),
            'shop_id' => self::mainShop()->id,
            'reference_no' => 'mtr-' . date("Ymd") . '-' . date("his"),
            'from_account_id' => $request->from_account_id,
            'to_account_id' => $request->to_account_id,
            'amount' => $request->amount,
        ]);
        return $create;
    }

    public static function updateByRequest(Request $request, MoneyTransfer $moneyTransfer)
    {
        $update = self::update($moneyTransfer, [
            'from_account_id' => $request->from_account_id,
            'to_account_id' => $request->to_account_id,
            'amount' => $request->amount,
        ]);
        return $update;
    }
}
