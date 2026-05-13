<?php

namespace App\Http\Controllers\Api;

use App\Enums\TransectionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\BalanceTransferRequest;
use App\Http\Resources\AccountResource;
use App\Models\GeneralSetting;
use App\Repositories\AccountRepository;
use App\Repositories\SaleRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\WalletRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function accounts()
    {
        $logoPath = GeneralSetting::latest()->first()?->logo->file ?? '/logo/logo.png';
        $accounts = AccountRepository::getAll();
        return $this->json('Account List', [
            'accounts' => AccountResource::collection($accounts),
            'logo_path' => $logoPath
        ]);
    }

    public function balance()
    {
        $wallet = auth()->user()->wallet;
        $todaySale = SaleRepository::query()->whereDate('created_at', Carbon::today())->sum('grand_total');

        return $this->json('Account List', [
            'balance' => $wallet->balance ?? 0,
            'todaySale' => $todaySale,
        ]);
    }

    public function balanceTransfer(BalanceTransferRequest $request)
    {
        $wallet = auth()->user()->wallet;
        if (!isset($wallet->balance) || $wallet->balance <= 0) {
            return $this->json('You have currently 0 balance', [], 422);
        }

        if ($wallet->balance < $request->amount) {
            return $this->json('You have extend your current balance', [], 422);
        }
        $request['transection_type'] = TransectionType::CREDIT->value;
        $account = AccountRepository::find($request->account_id);
        if($account){
            $balance = $account->total_balance + $request->amount;
            AccountRepository::balanceUpdate($balance, $account);
        }
        
        TransactionRepository::creditByRequest($request->payment_method, $request->amount, $request->account_id);
        WalletRepository::debit($wallet, $request->amount);
        

        return $this->json('Balance successfully transfered', [
            'balance' => $wallet->balance - $request->amount,
        ]);
    }
}
