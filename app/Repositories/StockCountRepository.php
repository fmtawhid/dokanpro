<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\StockCount;
use App\Traits\ShopTrait;
use Illuminate\Http\Request;

class StockCountRepository extends Repository
{
    use ShopTrait;

    public static function model()
    {
        return StockCount::class;
    }
    public static function storeByRequest(Request $request)
    {
        $categories = json_encode($request->category_id);
        $brands = json_encode($request->brand_id);

        $create = self::create([
            'created_by' => auth()->id(),
            'shop_id' => self::mainShop()->id,
            'reference_no' => 'scr-' . date('Y-m-d H:i:s'),
            'warehouse_id' => $request->warehouse_id,
            'category_id' => $request->category_id ? $categories : $request->category_id,
            'brand_id' => $request->brand_id ? $brands : $request->brand_id,
            'type' => $request->type,
            'initial_file' => date('Y-m-d H:i:s') . ".csv",
            'is_adjusted' => false,
        ]);

        return $create;
    }
}
