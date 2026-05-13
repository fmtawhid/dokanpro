<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\GeneralSetting;
use App\Models\Media;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GeneralSetting::create([
            'site_title' => 'ReadyPOS',
            'currency_id' => 1,
            'currency_position' => 'Prefix',
            'date_format' => 'd-m-Y',
            'date_with_time' => 'Enable',
            'address' => fake()->address(),
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'developed_by' => 'Razinsoft',
            'direction' => 'ltr',
            'lang' => 'en',
            'barcode_digits' => 8,
        ]);

        $shopGeneralSettings = [
            [
                'shop_id' => 1,
                'site_title' => 'ReadyPOS Shop',
                'currency_position' => 'Prefix',
                'date_format' => 'd-m-Y',
                'date_with_time' => 'Enable',
                'address' => fake()->address(),
                'email' => fake()->email(),
                'phone' => fake()->phoneNumber(),
                'developed_by' => 'ReadyPOS Shop',
                'direction' => 'ltr',
                'lang' => 'en',
                'barcode_digits' => 8,
            ],
            [
                'shop_id' => 2,
                'site_title' => 'Razin Grocery Shop',
                'currency_position' => 'Prefix',
                'date_format' => 'd-m-Y',
                'date_with_time' => 'Enable',
                'address' => fake()->address(),
                'email' => fake()->email(),
                'phone' => fake()->phoneNumber(),
                'developed_by' => 'Razin Grocery Shop',
                'direction' => 'ltr',
                'lang' => 'en',
                'barcode_digits' => 8,
            ],
            [
                'shop_id' => 3,
                'site_title' => 'Razin Pharmacy',
                'currency_position' => 'Prefix',
                'date_format' => 'd-m-Y',
                'date_with_time' => 'Enable',
                'address' => fake()->address(),
                'email' => fake()->email(),
                'phone' => fake()->phoneNumber(),
                'developed_by' => 'Razin Pharmacy',
                'direction' => 'ltr',
                'lang' => 'en',
                'barcode_digits' => 8,
            ],
            [
                'shop_id' => 4,
                'site_title' => 'Razin Electronics and Hardware shop',
                'currency_position' => 'Prefix',
                'date_format' => 'd-m-Y',
                'date_with_time' => 'Enable',
                'address' => fake()->address(),
                'email' => fake()->email(),
                'phone' => fake()->phoneNumber(),
                'developed_by' => 'Razin Electronics and Hardware shop',
                'direction' => 'ltr',
                'lang' => 'en',
                'barcode_digits' => 8,
            ],
            [
                'shop_id' => 5,
                'site_title' => 'Razin Restaurant',
                'currency_position' => 'Prefix',
                'date_format' => 'd-m-Y',
                'date_with_time' => 'Enable',
                'address' => fake()->address(),
                'email' => fake()->email(),
                'phone' => fake()->phoneNumber(),
                'developed_by' => 'Razin Restaurant',
                'direction' => 'ltr',
                'lang' => 'en',
                'barcode_digits' => 8,
            ],
            [
                'shop_id' => 6,
                'site_title' => 'Razin Clothing Shop',
                'currency_position' => 'Prefix',
                'date_format' => 'd-m-Y',
                'date_with_time' => 'Enable',
                'address' => fake()->address(),
                'email' => fake()->email(),
                'phone' => fake()->phoneNumber(),
                'developed_by' => 'Razin Clothing Shop',
                'direction' => 'ltr',
                'lang' => 'en',
                'barcode_digits' => 8,
            ],
        ];
        $images = ['logo.png', 'groceryLogo.png', 'pharmaLogo.png', 'mobileShopLogo.png', 'restaurantLogo.png', 'logo.png'];
        foreach ($shopGeneralSettings as $key => $shopGeneralSetting) {
            $image = Media::factory()->create([
                'src' => 'logo/' . $images[$key],
                'path' => 'logo/',
            ])->id;
            $currencyId = Currency::factory()->create([
                'shop_id' => $shopGeneralSetting['shop_id'],
            ])->id;
            GeneralSetting::create([
                'shop_id' => $shopGeneralSetting['shop_id'],
                'site_title' => $shopGeneralSetting['site_title'],
                'currency_id' => $currencyId,
                'currency_position' => $shopGeneralSetting['currency_position'],
                'date_format' => $shopGeneralSetting['date_format'],
                'date_with_time' => $shopGeneralSetting['date_with_time'],
                'address' => $shopGeneralSetting['address'],
                'email' => $shopGeneralSetting['email'],
                'phone' => $shopGeneralSetting['phone'],
                'developed_by' => $shopGeneralSetting['developed_by'],
                'direction' => $shopGeneralSetting['direction'],
                'lang' => $shopGeneralSetting['lang'],
                'barcode_digits' => $shopGeneralSetting['barcode_digits'],
                'logo_id' => $image
            ]);
        }
    }
}
