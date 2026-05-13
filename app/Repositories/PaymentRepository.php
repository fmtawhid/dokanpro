<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Enums\TransectionType;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentRepository extends Repository
{
    public static function model()
    {
        return Payment::class;
    }
    public static function storeByRequest(Request $request, $purchaseId)
    {
        $account = null;
        if ($request->payment_method == 'Bank') {
            $account = $request->account_id;
        }

        $amount = $request->paid_amount ? $request->paid_amount : 0;
        $storeByRequest = self::create([
            'purchase_id' => $purchaseId,
            'account_id' =>  $account,
            'user_id' => auth()->id(),
            'payment_reference' => 'ppr-' . date("Ymd") . '-' . date("his"),
            'amount' => $amount,
            'paying_method' => $request->payment_method,
            'payment_note' => $request->payment_note,
        ]);
        return $storeByRequest;
    }

    public static function addPayment(Request $request, $account)
    {
        $payment = self::create([
            'purchase_id' => $request->purchase_id,
            'account_id' =>  $account?->id,
            'user_id' => auth()->id(),
            'payment_reference' => 'ppr-' . date("Ymd") . '-' . date("his"),
            'amount' => $request->paid_amount,
            'paying_method' => $request->payment_method,
            'payment_note' => $request->payment_note,
        ]);

        return $payment;
    }
}
