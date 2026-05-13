<?php

use App\Repositories\GeneralSettingRepository;
use Carbon\Carbon;

function dateFormat($date)
{
    $generalSettings = GeneralSettingRepository::query()->whereNull('shop_id')->latest()->first();
    $mainShop = mainShop();
    if ($mainShop) {
        $generalSettings = GeneralSettingRepository::query()->where('shop_id', $mainShop->id)->first();
    }
    $format = $generalSettings->date_format->value ?? 'd-m-Y';
    $date = Carbon::parse($date)->format($format);

    if ($generalSettings->date_with_time->value == 'Enable') {
        $date = Carbon::parse($date)->format($format . ' h:m:s');
    }
    return $date;
}

function numberFormat($number)
{
    $generalSettings = GeneralSettingRepository::query()->whereNull('shop_id')->latest()->first();
    $mainShop = mainShop();
    if ($mainShop) {
        $generalSettings = GeneralSettingRepository::query()->where('shop_id', $mainShop->id)->first();
    }
    $symbol = $generalSettings->defaultCurrency->symbol ?? '$';
    if (isset($generalSettings->currency_position) && ($generalSettings->currency_position->value == "Prefix")) {

        return $symbol . ' ' . number_format($number, 2);
    }

    return number_format($number, 2) . ' ' . $symbol;
}

function feature($feature)
{
    $shop = mainShop();
    if (!$shop) {
        return false;
    }
    
    // Query directly from database to avoid Eloquent model caching issues
    // This ensures we always get the most current feature status
    $exists = \DB::table('business_modules_shop')
        ->join('business_modules', 'business_modules_shop.business_modules_id', '=', 'business_modules.id')
        ->where('business_modules_shop.shop_id', $shop->id)
        ->where('business_modules.name', $feature)
        ->exists();
    
    return $exists;
}

function mainShop()
{
    $user = auth()->user();
    $mainShop = $user->shopUser->first();
    return match ($mainShop) {
        null => $user->shop,
        default => $mainShop
    };
}
