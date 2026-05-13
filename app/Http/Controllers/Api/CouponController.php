<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CouponRepository;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function applyPromoCode(){
        $request = request();
        $couponCode = CouponRepository::query()->where('code', $request->code)->first();
        if (!$couponCode) {
            return $this->json('Promo Code Not Match!', [], 422);
        }
        if($couponCode->min_amount && $couponCode->min_amount > $request->price){
            return $this->json('Minimum Purchase Amount is '.$couponCode->min_amount, [], 422);
        }
        if($couponCode->max_amount && $couponCode->max_amount < $request->price){
            return $this->json('Maximum Purchase Amount is '.$couponCode->max_amount, [], 422);
        }
        
        $discount = $couponCode->amount;
        if ($couponCode->type->value == 'Percentage') {
            $discount = $request->price * $couponCode->amount / 100;
        }
        
        return $this->json('Coupon succesfully applied', [
            'id' => $couponCode->id,
            'discount' => $discount,
        ]);
    }
}
