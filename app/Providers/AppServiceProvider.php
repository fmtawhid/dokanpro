<?php

namespace App\Providers;

use App\Repositories\GeneralSettingRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        view()->composer('*', function ($view) {
            try {
                $generalSettings = GeneralSettingRepository::query()->whereNull('shop_id')->latest()->first();
                $mainShop = auth()->user() ? $this->mainShop() : null;
                if ($mainShop) {
                    $generalSettings = GeneralSettingRepository::query()->where('shop_id', $mainShop->id)->first();
                }
            } catch (\Throwable $th) {
                $generalSettings = null;
                $mainShop = null;
            }

            $seederRun = true;
            if ($generalSettings) {
                $seederRun = false;
            }

            $storageLink  = true;
            if (file_exists(public_path('storage'))) {
                $storageLink = false;
            }

            $view->with('seederRun', $seederRun);
            $view->with('storageLink', $storageLink);
            $currency = $generalSettings?->defaultCurrency;
            $view->with('general_settings', $generalSettings);
            $view->with('currency', $currency);
            $view->with('mainShop', $mainShop);
        });
    }

    private function mainShop()
    {
        $user = auth()->user();
        $mainShop = $user->shopUser->first();

        return match ($mainShop) {
            null => $user->shop,
            default => $mainShop
        };
    }
}
