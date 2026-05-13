<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('json/categoriesWiseProducts.json'));
        $categories = json_decode($json);
        foreach ($categories as $category) {
            $cate = Category::factory()->create([
                'name' => $category->name,
                'thumbnail_id' => Media::factory()->create(),
            ]);
            if (isset($category->products) && $category->products) {
                foreach ($category->products as $product) {
                    Product::factory()->create([
                        'name' => $product->name,
                        'category_id' => $cate->id,
                        'price' => $product->price,
                        'cost' => $product->cost,
                        'thumbnail_id' => Media::factory()->create(),
                    ]);
                }
            }
        }
    }
}
