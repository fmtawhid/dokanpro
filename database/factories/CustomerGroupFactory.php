<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerGroup>
 */
class CustomerGroupFactory extends Factory
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
            'percentage' => $this->faker->randomElement([10, 15, 20])
        ];
    }
}
