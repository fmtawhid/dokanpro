<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Enums\IsHas;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Enums\SubscriptionApprovalStatus;
use App\Models\ShopSubscription;
use App\Models\SubscriptionRequest as ModelsSubscriptionRequest;
use App\Traits\ShopTrait;
use Carbon\Carbon;

class ShopSubscriptionRepository extends Repository
{
    use ShopTrait;
    
    public static function model()
    {
        return ShopSubscription::class;
    }

    public static function storeByRequest(ModelsSubscriptionRequest $subscriptionRequest, $payment_gateway = false)
    {
        $subscription = $subscriptionRequest->subscription;
        $date = now();
        if ($subscription->recurring_type->value == 'Onetime') {
            $expiredAt = now();
        } elseif ($subscription->recurring_type->value == 'Weekly') {
            $expiredAt = Carbon::parse($date)->addDays(7);
        } elseif ($subscription->recurring_type->value == 'Monthly') {
            $expiredAt = Carbon::parse($date)->addMonths(1);
        } elseif ($subscription->recurring_type->value == 'Yearly') {
            $expiredAt = Carbon::parse($date)->addYears(1);
        }

        return self::create([
            'shop_id' => self::mainShop()->id,
            'subscription_id' => $subscriptionRequest->subscription_id,
            'is_current' => IsHas::YES->value,
            'payment_status' => PaymentStatus::PAID->value,
            'payment_gateway' => $payment_gateway,
            'expired_at' => $expiredAt,
            'status' => SubscriptionApprovalStatus::APPROVED->value
        ]);
    }

    public static function storePendingByRequest(ModelsSubscriptionRequest $subscriptionRequest, $payment_gateway = false)
    {
        $subscription = $subscriptionRequest->subscription;
        $date = now();
        if ($subscription->recurring_type->value == 'Onetime') {
            $expiredAt = now();
        } elseif ($subscription->recurring_type->value == 'Weekly') {
            $expiredAt = Carbon::parse($date)->addDays(7);
        } elseif ($subscription->recurring_type->value == 'Monthly') {
            $expiredAt = Carbon::parse($date)->addMonths(1);
        } elseif ($subscription->recurring_type->value == 'Yearly') {
            $expiredAt = Carbon::parse($date)->addYears(1);
        }

        return self::create([
            'shop_id' => self::mainShop()->id,
            'subscription_id' => $subscriptionRequest->subscription_id,
            'is_current' => IsHas::YES->value,
            'payment_status' => PaymentStatus::UNPAID->value,
            'payment_gateway' => $payment_gateway,
            'expired_at' => $expiredAt,
            'status' => SubscriptionApprovalStatus::PENDING->value
        ]);
    }

    public static function ShopSubscriptionChanage($request, $shop)
    {
        $subscription = SubscriptionRepository::find($request->subscription_id);
        $date = now();
        if ($subscription->recurring_type->value == 'Onetime') {
            $expiredAt = now();
        } elseif ($subscription->recurring_type->value == 'Weekly') {
            $expiredAt = Carbon::parse($date)->addDays(7);
        } elseif ($subscription->recurring_type->value == 'Monthly') {
            $expiredAt = Carbon::parse($date)->addMonths(1);
        } elseif ($subscription->recurring_type->value == 'Yearly') {
            $expiredAt = Carbon::parse($date)->addYears(1);
        }
        return self::create([
            'shop_id' => $shop->id,
            'subscription_id' => $request->subscription_id,
            'is_current' => IsHas::YES->value,
            'payment_status' => PaymentStatus::PAID->value,
            'payment_gateway' => PaymentGateway::STRIPE->value,
            'expired_at' => $expiredAt,
            'status' => SubscriptionApprovalStatus::APPROVED->value
        ]);
    }

    public static function approveSubscription(ShopSubscription $shopSubscription)
    {
        return self::update($shopSubscription, [
            'status' => SubscriptionApprovalStatus::APPROVED->value,
            'payment_status' => PaymentStatus::PAID->value,
        ]);
    }

    public static function rejectSubscription(ShopSubscription $shopSubscription)
    {
        return self::update($shopSubscription, [
            'status' => SubscriptionApprovalStatus::REJECTED->value,
            'payment_status' => PaymentStatus::UNPAID->value,
        ]);
    }
}
