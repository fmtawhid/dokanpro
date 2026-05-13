<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\WarehouseRequest;
use App\Models\Warehouse;
use App\Traits\ShopTrait;

class WarehouseRepository extends Repository
{
    use ShopTrait;
    public static function model()
    {
        return Warehouse::class;
    }

    public static function storeByRequest(WarehouseRequest $request): Warehouse
    {
        return self::create([
            'created_by' => auth()->id(),
            'shop_id' => self::mainShop()->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address
        ]);
    }

    public static function updateByRequest(WarehouseRequest $request, Warehouse $warehouse): Warehouse
    {
        self::update($warehouse, [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        return $warehouse;
    }
}
