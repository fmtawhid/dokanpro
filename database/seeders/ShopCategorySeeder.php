<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\ShopCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shopCategories = [
            [
                'name' => 'All in One',
                'primary_color' => '#29aae1',
                'secondary_color' => '#eaf7fc',
            ],
            [
                'name' => 'Super Shop or Grocery Shop',
                'primary_color' => '#50B94C',
                'secondary_color' => '#50b94c29',
            ],
            [
                'name' => 'Pharmacy',
                'primary_color' => '#FD151B',
                'secondary_color' => '#fd151b1f',
            ],
            [
                'name' => 'Electronics/Hardware/Mobile Shop',
                'primary_color' => '#000000',
                'secondary_color' => '#0000001f',
            ],
            [
                'name' => 'Restaurant',
                'primary_color' => '#FFCF2D',
                'secondary_color' => '#ffcf2d26',
            ],
            [
                'name' => 'Clothing Shop',
                'primary_color' => '#141727',
                'secondary_color' => '#14172726',
            ]
        ];
        foreach ($shopCategories as $shopCategory) {
            ShopCategory::create([
                'created_by' => 1,
                'name' => $shopCategory['name'],
                'primary_color' => $shopCategory['primary_color'],
                'secondary_color' => $shopCategory['secondary_color'],
                'description' => fake()->text(80),
                'status' => Status::ACTIVE->value,
            ]);
        }
    }
}
#ef444424