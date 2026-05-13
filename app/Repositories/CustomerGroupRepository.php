<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\CustomerGroup;
use App\Traits\ShopTrait;
use Illuminate\Http\Request;

class CustomerGroupRepository extends Repository
{
    use ShopTrait;
    public static function model()
    {
        return CustomerGroup::class;
    }
    public static function storeByRequest(Request $request)
    {
        return self::create([
            'created_by' => auth()->id(),
            'shop_id' => self::mainShop()->id,
            'name' => $request->name,
            'percentage' => $request->percentage,
        ]);
    }

    public static function updateByRequest(Request $request, CustomerGroup $customergroup)
    {
        return self::update($customergroup, [
            'name' => $request->name,
            'percentage' => $request->percentage
        ]);
    }
}
