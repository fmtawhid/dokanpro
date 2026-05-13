<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\Account;
use App\Traits\ShopTrait;
use Illuminate\Http\Request;

class AccountRepository extends Repository
{
    use ShopTrait;
    public static function model()
    {
        return Account::class;
    }
    public static function storeByRequest(Request $request)
    {
        $account = self::create([
            'created_by' => auth()->id(),
            'shop_id' => self::mainShop()->id,
            'name' => $request->name,
            'account_no' => $request->account_no,
            'note' => $request->note,
            'total_balance' => $request->balance,
            'is_default' => 1,
        ]);
        
        WalletRepository::create([
            'account_id' => $account->id,
            'balance' => $request->balance
        ]);

        return $account;
    }

    public static function updateByRequest(Request $request, Account $account)
    {
        $updateBlance = $account->total_balance + $request->initial_balance;
        $update = self::update($account, [
            'name' => $request->name,
            'account_no' => $request->account_no,
            'initial_balance' => 0,
            'note' => $request->note,
            'total_balance' => $updateBlance,
        ]);

        return $update;
    }

    public static function addBalance(Request $request, Account $account)
    {
        $updateBlance = $account->total_balance + $request->total_balance;
        $update = self::update($account, [
            'total_balance' => $updateBlance,
        ]);
        return $update;
    }

    public static function balanceUpdate($totalBalance, Account $account)
    {
        return self::update($account, [
            'total_balance' => $totalBalance
        ]);
    }
}
