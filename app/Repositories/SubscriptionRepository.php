<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Subscription;

class SubscriptionRepository extends Repository
{
    public static function model()
    {
        return Subscription::class;
    }

    public static function storeByRequest(SubscriptionRequest $request)
    {
        return self::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'shop_limit' => $request->shop_limit,
            'product_limit' => $request->product_limit,
            'recurring_type' => $request->recurring_type,
            'status' => $request->status,
        ]);
    }

    public static function updateByRequest(SubscriptionRequest $request, Subscription $subscription)
    {
        return self::update($subscription, [
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'shop_limit' => $request->shop_limit,
            'product_limit' => $request->product_limit,
            'recurring_type' => $request->recurring_type,
            'status' => $request->status,
        ]);
    }

    public static function statusChanageByRequest(Subscription $subscription, $status)
    {
        return self::update($subscription, [
            'status' => $status,
        ]);
    }
}
