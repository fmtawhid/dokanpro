<?php

if (!function_exists('hasSubscriptionFeature')) {
    /**
     * Check if current shop has a subscription feature
     * Usage in Blade: @if(hasSubscriptionFeature('hrm'))
     * 
     * @param string $featureSlug
     * @return bool
     */
    function hasSubscriptionFeature($featureSlug)
    {
        return \App\Helpers\SubscriptionHelper::hasFeature($featureSlug);
    }
}

if (!function_exists('getEnabledSubscriptionFeatures')) {
    /**
     * Get all enabled features for current shop
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function getEnabledSubscriptionFeatures()
    {
        return \App\Helpers\SubscriptionHelper::getEnabledFeatures();
    }
}

if (!function_exists('getEnabledFeatureSlugs')) {
    /**
     * Get array of enabled feature slugs
     * 
     * @return array
     */
    function getEnabledFeatureSlugs()
    {
        return \App\Helpers\SubscriptionHelper::getEnabledFeatureSlugs();
    }
}
