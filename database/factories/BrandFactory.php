<?php

namespace Database\Factories;

use App\Models\Media;
use App\Repositories\MediaRepository;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
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
            'title' => $this->faker->name,
            'thumbnail_id' => Media::factory()->create(),
        ];
    }
}
