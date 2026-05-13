<?php

namespace Database\Factories;

use App\Models\Media;
use App\Repositories\MediaRepository;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
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
            'name' => $this->faker->name,
            'thumbnail_id' => Media::factory()->create(),
            'company_name' => $this->faker->company(),
            'phone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->email(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
        ];
    }
}
