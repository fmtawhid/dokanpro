<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\UnitRequest;
use App\Models\Unit;
use App\Traits\ShopTrait;

class UnitRepository extends Repository
{
    use ShopTrait;
    
    public static function model()
    {
        return Unit::class;
    }
    public static function storeByRequest(UnitRequest $request)
    {
        $create = self::create([
            'created_by' => auth()->id(),
            'shop_id' => self::mainShop()->id,
            'code' => $request->code,
            'name' => $request->name,
            'base_unit_id' => $request->base_unit_id,
            'operator' => $request->operator,
            'operation_value' => $request->operation_value
        ]);

        return $create;
    }

    public static function updateByRequest(UnitRequest $request, Unit $unit)
    {
        $update = self::update($unit, [
            'code' => $request->code,
            'name' => $request->name,
            'base_unit_id' => $request->base_unit_id,
            'operator' => $request->operator,
            'operation_value' => $request->operation_value,
        ]);

        return $update;
    }
}
