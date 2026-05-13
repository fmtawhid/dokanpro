<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\SubscriptionFeature;
use Illuminate\Database\Eloquent\Collection;

class SubscriptionFeatureRepository extends Repository
{
    public static function model()
    {
        return SubscriptionFeature::class;
    }

    public static function attachFeaturesToSubscription($subscription, $features)
    {
        // $features should be array like: ['hrm' => 100, 'accounting' => 100, ...]
        $attachData = [];
        
        foreach ($features as $featureSlug => $price) {
            $feature = self::query()->where('slug', $featureSlug)->first();
            if ($feature) {
                $attachData[$feature->id] = ['price' => $price];
            }
        }

        if (!empty($attachData)) {
            $subscription->features()->sync($attachData);
        }
    }

    public static function getFeaturesBySubscription($subscription)
    {
        return $subscription->features()->get();
    }

    public static function getAvailableFeatures()
    {
        return self::query()->get();
    }

    public static function enableFeatureForShop($shopSubscription, $featureIds)
    {
        // Attach features to shop subscription
        $attachData = [];
        
        foreach ($featureIds as $featureId) {
            $feature = self::query()->find($featureId);
            if ($feature) {
                $price = $shopSubscription->subscription->features()
                    ->where('subscription_features.id', $featureId)
                    ->pluck('subscription_feature.price')
                    ->first() ?? 0;
                    
                $attachData[$featureId] = [
                    'price' => $price,
                    'expired_at' => $shopSubscription->expired_at
                ];
            }
        }

        if (!empty($attachData)) {
            $shopSubscription->features()->sync($attachData);
        }

        return $shopSubscription;
    }

    public static function getEnabledFeaturesForShop($shop)
    {
        if (!$shop || !$shop->currentSubscriptions()) {
            return collect();
        }

        $currentSubscription = $shop->currentSubscriptions();
        return $currentSubscription->features()->get();
    }

    public static function isFeatureEnabledForShop($shop, $featureSlug)
    {
        if (!$shop || !$shop->currentSubscriptions()) {
            return false;
        }

        $currentSubscription = $shop->currentSubscriptions();
        return $currentSubscription->features()
            ->where('slug', $featureSlug)
            ->where('shop_subscription_features.expired_at', '>=', now())
            ->exists();
    }
}
