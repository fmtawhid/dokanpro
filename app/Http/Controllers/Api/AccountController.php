<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;
use App\Http\Resources\BalanceSheetResource;
use App\Http\Resources\MoneyTransferResource;
use App\Models\MoneyTransfer;
use App\Repositories\AccountRepository;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $type = $request->type;

        $page = $request->page;
        $perPage = $request->per_page;
        $skip = ($page * $perPage) - $perPage;

        if ($type == 'money_transfer') {

            $moneyTransfers = MoneyTransfer::query()->where('shop_id', $this->mainShop()->id)
                ->when($search, function ($query) use ($search) {
                })->latest();
            $total = $moneyTransfers->count();

            $moneyTransfers = $moneyTransfers->when($page && $perPage, function ($query) use ($skip, $perPage) {
                return $query->skip($skip)->take($perPage);
            })->get();

            return $this->json("account list", [
                'total' => $total,
                'money_transfers' => MoneyTransferResource::collection($moneyTransfers)
            ]);
        } elseif ($type == 'balance_sheet') {

            $balanceSheet = AccountRepository::query()->where('shop_id', $this->mainShop()->id)
                ->when($search, function ($query) use ($search) {
                    return $query->where('name', 'like', "%$search%")
                        ->orWhere('account_no', 'like', "%$search%");
                })->latest();

            $total = $balanceSheet->count();

            $balanceSheet = $balanceSheet->when($page && $perPage, function ($query) use ($skip, $perPage) {
                return $query->skip($skip)->take($perPage);
            })->get();

            return $this->json("balance sheet", [
                'total' => $total,
                'balance_sheet' => BalanceSheetResource::collection($balanceSheet)
            ]);
        }

        $accounts = AccountRepository::query()->where('shop_id', $this->mainShop()->id)
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%$search%")
                    ->orWhere('account_no', 'like', "%$search%");
            })->latest();

        $total = $accounts->count();

        $accounts = $accounts->when($page && $perPage, function ($query) use ($skip, $perPage) {
            return $query->skip($skip)->take($perPage);
        })->get();

        return $this->json("account list", [
            'total' => $total,
            'accounts' => AccountResource::collection($accounts)
        ]);
    }
}
