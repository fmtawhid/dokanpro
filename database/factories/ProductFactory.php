<?php

namespace Database\Factories;

use App\Enums\BarcodeSymbology;
use App\Enums\DiscountType;
use App\Models\Media;
use App\Repositories\BrandRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\TaxRepository;
use App\Repositories\UnitRepository;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brand = $this->faker->randomElement(BrandRepository::getAll());
        $tax = $this->faker->randomElement(TaxRepository::getAll());
        $unit = $this->faker->randomElement(UnitRepository::getAll());
        $category = $this->faker->randomElement(CategoryRepository::getAll());
        $code = mt_rand(10000000, 99999999);

        return [
            'shop_id' => 1,
            'created_by' => 2,
            'name' => $this->faker->name,
            'code' =>  $code,
            'type' => 'Standard',
            'barcode_symbology' => BarcodeSymbology::CODE_128->value,
            'brand_id' => $brand->id,
            'tax_id' => $tax->id,
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'qty' => 0,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'thumbnail_id' => Media::factory()->create(),
            'product_details' => $this->faker->paragraph,
            'is_featured' => 1
        ];
    }
}
