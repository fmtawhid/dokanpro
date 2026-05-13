<?php

namespace App\Http\Controllers;

use App\Enums\CouponType;
use App\Http\Requests\CouponRequest;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Repositories\CouponRepository;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = CouponRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        $couponTypes = CouponType::cases();
        return view('coupon.index', compact('coupons', 'couponTypes'));
    }

    public function store(CouponRequest $request)
    {
        CouponRepository::storeByRequest($request);
        return back()->with('success', 'Coupon is created successfully!');
    }

    public function update(CouponRequest $request, Coupon $coupon)
    {
        CouponRepository::updateByRequest($request, $coupon);
        return back()->with('success', 'Coupon is updated successfully!');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Coupon is deleted successfully!');
    }
    public function applyPromoCode()
    {
        $request = request();
        $couponCode = CouponRepository::query()->where('code', $request->code)->first();
        if (!$couponCode) {
            return $this->json('Promo Code Not Match!', [], 422);
        }
        if ($couponCode->min_amount && $couponCode->min_amount > $request->price) {
            return $this->json('Minimum Purchase Amount is ' . $couponCode->min_amount, [], 422);
        }
        if ($couponCode->max_amount && $couponCode->max_amount < $request->price) {
            return $this->json('Maximum Purchase Amount is ' . $couponCode->max_amount, [], 422);
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
