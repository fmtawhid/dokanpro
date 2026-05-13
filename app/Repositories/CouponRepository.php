<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use App\Traits\ShopTrait;

class CouponRepository extends Repository
{
    use ShopTrait;
    public static function model()
    {
        return Coupon::class;
    }
    public static function storeByRequest(CouponRequest $request)
    {
        return self::create([
            'created_by' => auth()->id(),
            'shop_id' => self::mainShop()->id,
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'min_amount' => $request->type == 'Percentage' ? 0 : $request->minimum_amount,
            'max_amount' => $request->type == 'Percentage' ? 0 : $request->maximum_amount,
            'amount' => $request->amount,
            'qty' => $request->quantity,
            'expired_at' => $request->expired_date
        ]);
    }
    public static function updateByRequest(CouponRequest $request, Coupon $coupon)
    {
        $update = self::update($coupon, [
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'min_amount' => $request->type == 'Percentage' ? 0 : $request->minimum_amount,
            'max_amount' => $request->type == 'Percentage' ? 0 : $request->maximum_amount,
            'amount' => $request->amount,
            'qty' => $request->quantity,
            'expired_at' => $request->expired_date,
        ]);

        return $update;
    }
}
