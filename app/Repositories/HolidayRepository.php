<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\HolidayRequest;
use App\Models\Holiday;
use App\Traits\ShopTrait;

class HolidayRepository extends Repository
{
    use ShopTrait;
    
    public static function model()
    {
        return Holiday::class;
    }
    public static function storeByRequest(HolidayRequest $request)
    {
        return self::create([
            'created_by' => auth()->id(),
            'shop_id' => self::mainShop()->id,
            'reason' => $request->reason,
            'from' => $request->from,
            'to' => $request->to,
        ]);
    }
    public static function updateByRequest(HolidayRequest $request, Holiday $holiday)
    {
        $update = self::update($holiday, [
            'reason' => $request->reason,
            'from' => $request->from,
            'to' => $request->to,
        ]);
        return $update;
    }
}
