<?php

namespace Database\Factories;

use App\Models\Media;
use App\Repositories\MediaRepository;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'shop_id' => 1,
            'created_by' => 2,
            'thumbnail_id' => Media::factory()->create(),
            'name' => $this->faker->name,
            'parent_id' => 1,
        ];
    }
}
