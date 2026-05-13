<?php

namespace App\Traits;


trait ShopTrait {
    public static function mainShop()
    {
        $user = auth()->user();
        $mainShop = $user->shopUser->first();

        return match ($mainShop) {
            null => $user->shop,
            default => $mainShop
        };
    }
}
