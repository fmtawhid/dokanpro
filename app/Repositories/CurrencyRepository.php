<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\CurrencyRequest;
use App\Models\Currency;
use App\Models\Shop;
use App\Models\User;
use App\Traits\ShopTrait;

class CurrencyRepository extends Repository
{
    use ShopTrait;
    public static function model()
    {
        return Currency::class;
    }

    public static function storeByRequest(CurrencyRequest $request)
    {
        return self::create([
            'created_by' => auth()->id(),
            'shop_id' => self::mainShop()?->id,
            'name' => $request->name,
            'symbol' => $request->symbol,
            'code' => $request->code
        ]);
    }

    public static function updateByRequest(CurrencyRequest $request, Currency $currency)
    {
        $update = self::update($currency, [
            'created_by' => auth()->id(),
            'name' => $request->name,
            'symbol' => $request->symbol,
            'code' => $request->code
        ]);

        return $update;
    }
    public static function defaultCurrency(Shop $shop, User $user)
    {
        return self::create([
            'created_by' => $user->id,
            'shop_id' => $shop->id,
            'name' => 'US Dollar',
            'code' => 'USD',
            'symbol' => '$'
        ]);
    }
}
