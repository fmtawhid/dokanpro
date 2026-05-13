<?php

namespace App\Helpers;

use App\Repositories\SubscriptionFeatureRepository;

class SubscriptionHelper
{
    /**
     * Get current user's main shop
     * 
     * @return \App\Models\Shop|null
     */
    private static function getMainShop()
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return null;
            }
            
            // Get shop user (merchant shop)
            $shopUser = $user->shopUser()->first();
            if ($shopUser) {
                return $shopUser;
            }
            
            // Fallback to user's shop
            return $user->shop ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Check if current shop has a specific subscription feature
     * 
     * @param string $featureSlug - Feature slug like 'hrm', 'accounting', 'return', 'expense'
     * @return bool
     */
    public static function hasFeature($featureSlug)
    {
        try {
            $shop = self::getMainShop();
            if (!$shop) {
                return false;
            }
            return SubscriptionFeatureRepository::isFeatureEnabledForShop($shop, $featureSlug);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get all enabled features for current shop
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getEnabledFeatures()
    {
        try {
            $shop = self::getMainShop();
            if (!$shop) {
                return collect();
            }
            return SubscriptionFeatureRepository::getEnabledFeaturesForShop($shop);
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Get list of enabled feature slugs
     * 
     * @return array
     */
    public static function getEnabledFeatureSlugs()
    {
        return self::getEnabledFeatures()->pluck('slug')->toArray();
    }

    /**
     * Check if feature is enabled (global function wrapper)
     * 
     * @param string $featureSlug
     * @return bool
     */
    public static function feature($featureSlug)
    {
        return self::hasFeature($featureSlug);
    }
}
