<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Repositories\CurrencyRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencys = [
            [
                'name' => 'US Dollar',
                'code' => 'USD',
                'symbol' => '$',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Euro',
                'code' => 'EURO',
                'symbol' => '€',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Taka',
                'code' => 'TAKA',
                'symbol' => '৳',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        Currency::insert($currencys);
    }
}
