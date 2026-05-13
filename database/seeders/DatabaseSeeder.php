<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ShopCategorySeeder::class);
        $this->call(ShopSeeder::class);
        $this->call(ShopUserSeed::class);
        $this->call(StoreSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(GeneralSettingSeeder::class);
        $this->call(SubscriptionSeeder::class);
        $this->call(PaymentGatewaySeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(BusinessModulesSeeder::class);

        if (app()->environment('local')) {
            $this->call([
                CustomerGroupSeeder::class,
                CustomerSeeder::class,
                BrandSeeder::class,
                TaxSeeder::class,
                WarehouseSeeder::class,
                SupplierSeeder::class,
                CouponSeeder::class,
                CategorySeeder::class,
            ]);
        }
    }
}
