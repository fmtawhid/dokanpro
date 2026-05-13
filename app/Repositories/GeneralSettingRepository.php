<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\GeneralSetting;
use App\Models\Shop;
use Illuminate\Http\Request;

class GeneralSettingRepository extends Repository
{
    private static $path = '/settings';

    public static function model()
    {
        return GeneralSetting::class;
    }

    public static function storeByRequest(Request $request, Shop $shop, $currency = null)
    {
        $siteLogoId = null;
        if ($request->hasFile('shop_logo')) {
            try {
                $siteLogo = (new MediaRepository())->updateOrCreateByRequest(
                    $request->shop_logo,
                    self::$path,
                    'Image'
                );
                $siteLogoId = $siteLogo->id;
            } catch (\Exception $e) {
                \Log::warning('Failed to upload site logo: ' . $e->getMessage());
                // Continue without logo
            }
        }

        $smallLogoId = null;
        if ($request->hasFile('shop_logo')) {
            try {
                $smallLogo = (new MediaRepository())->updateOrCreateByRequest(
                    $request->shop_logo,
                    self::$path,
                    'Image'
                );
                $smallLogoId = $smallLogo->id;
            } catch (\Exception $e) {
                \Log::warning('Failed to upload small logo: ' . $e->getMessage());
                // Continue without logo
            }
        }

        $faviconId = null;
        if ($request->hasFile('shop_favicon')) {
            try {
                $favicon = (new MediaRepository())->updateOrCreateByRequest(
                    $request->shop_favicon,
                    self::$path,
                    'Image'
                );
                $faviconId = $favicon->id;
            } catch (\Exception $e) {
                \Log::warning('Failed to upload favicon: ' . $e->getMessage());
                // Continue without favicon
            }
        }
        return self::create([
            'shop_id' => $shop->id,
            'site_title' => $request->shop_name,
            'logo_id' => $siteLogoId,
            'small_logo_id' => $smallLogoId,
            'fav_id' => $faviconId,
            'currency_id' => $currency ? $currency->id : 1,
            'currency_position' => 'Prefix',
            'date_format' => 'd-m-Y',
            'date_with_time' => 'Enable',
            'address' => null,
            'email' => null,
            'phone' => null,
            'developed_by' => $request->shop_name,
            'direction' => 'ltr',
            'lang' => 'en',
            'barcode_digits' => 8,
        ]);
    }

    public static function updateByRequest(Request $request, $settings)
    {
        $siteLogoId = $settings->logo_id;
        if ($request->hasFile('site_logo')) {
            try {
                $siteLogo = (new MediaRepository())->updateOrCreateByRequest(
                    $request->site_logo,
                    self::$path,
                    'Image',
                    $settings->logo
                );
                $siteLogoId = $siteLogo->id;
            } catch (\Exception $e) {
                \Log::warning('Failed to upload site logo: ' . $e->getMessage());
            }
        }

        $smallLogoId = $settings->small_logo_id;
        if ($request->hasFile('small_logo')) {
            try {
                $smallLogo = (new MediaRepository())->updateOrCreateByRequest(
                    $request->small_logo,
                    self::$path,
                    'Image',
                    $settings->smallLogo
                );
                $smallLogoId = $smallLogo->id;
            } catch (\Exception $e) {
                \Log::warning('Failed to upload small logo: ' . $e->getMessage());
            }
        }

        $faviconId = $settings->fav_id;
        if ($request->hasFile('favicon')) {
            try {
                $favicon = (new MediaRepository())->updateOrCreateByRequest(
                    $request->favicon,
                    self::$path,
                    'Image',
                    $settings->favicon
                );
                $faviconId = $favicon->id;
            } catch (\Exception $e) {
                \Log::warning('Failed to upload favicon: ' . $e->getMessage());
            }
        }
        return self::update($settings, [
            'site_title' => $request->site_title,
            'currency_id' => $request->currency_id,
            'currency_position' => $request->currency_position,
            'date_format' => $request->date_format,
            'date_with_time' => $request->date_with_time,
            'developed_by' => $request->developed_by,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'logo_id' => $siteLogoId,
            'small_logo_id' => $smallLogoId,
            'fav_id' => $faviconId,
            'direction' => $request->direction,
            'barcode_digits' => $request->barcode_digits ?? 8,
            'copyright_text' => $request->copyright_text,
            'copyright_url' => $request->copyright_url,
            'dark_mode' => $request->dark_mode,
        ]);
    }

    public static function languageUpdate($lang)
    {
        $generalSettings = GeneralSettingRepository::query()->whereNull('shop_id')->latest()->first();
        $update = self::update($generalSettings, [
            'lang' => $lang
        ]);
        return $update;
    }
}
