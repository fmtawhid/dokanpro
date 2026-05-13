<?php

namespace App\Traits;

use App\Repositories\SubscriptionFeatureRepository;

trait FeatureAccessTrait
{
    /**
     * Check if a feature is enabled for the current shop
     */
    public function isFeatureEnabled($featureSlug)
    {
        $shop = $this->mainShop();
        if (!$shop) {
            return false;
        }

        return SubscriptionFeatureRepository::isFeatureEnabledForShop($shop, $featureSlug);
    }

    /**
     * Get all enabled features for the current shop
     */
    public function getEnabledFeatures()
    {
        $shop = $this->mainShop();
        if (!$shop) {
            return collect();
        }

        return SubscriptionFeatureRepository::getEnabledFeaturesForShop($shop);
    }

    /**
     * Check if a feature is enabled, if not throw an exception
     */
    public function requireFeature($featureSlug)
    {
        if (!$this->isFeatureEnabled($featureSlug)) {
            abort(403, "Feature '{$featureSlug}' is not available in your subscription plan. Please upgrade your plan to access this feature.");
        }
    }
}
