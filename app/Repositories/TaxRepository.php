<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\TaxRequest;
use App\Models\Tax;
use App\Traits\ShopTrait;

class TaxRepository extends Repository
{
    use ShopTrait;

    public static function model()
    {
        return Tax::class;
    }

    public static function storeByRequest(TaxRequest $request)
    {
        return self::create([
            'created_by' => auth()->id(),
            'shop_id' => self::mainShop()->id,
            'name' => $request->name,
            'rate' => $request->rate
        ]);
    }

    public static function updateByRequest(TaxRequest $request, Tax $tax)
    {
        $tax = self::update($tax, [
            'name' => $request->name,
            'rate' => $request->rate
        ]);

        return $tax;
    }
}
