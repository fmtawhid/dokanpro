<?php

namespace App\Repositories;

use App\Http\Requests\OrangePayRequest;
use App\Models\OrangePay;

class OrangePayRepository extends Repository
{
    public static function model()
    {
        return OrangePay::class;
    }

    public static function storeOrUpdateByRequest(OrangePayRequest $request, $orangePay)
    {
        return self::query()->updateOrCreate([
            'id' => $orangePay?->id ?? 0,
        ], [
            'shop_id' => self::mainShop()->id ?? null,
            'client_secret' => $request->client_secret,
            'client_id' => $request->client_id,
            'msisdn' => $request->msisdn,
            'pin_code' => $request->pin_code,
            'merchant_code' => $request->merchant_code,
        ]);
    }
}
