<?php

namespace Database\Factories;

use App\Enums\CouponType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    public function definition(): array
    {
        return [
            'shop_id' => 1,
            'created_by' => 2,
            'name' => $this->faker->name,
            'amount' => $this->faker->randomElement([5, 10, 15, 20]),
            'type' => $this->faker->randomElement(CouponType::cases()),
            'code' => $this->faker->regexify('[A-Z0-9]{10}'),
            'max_amount' => $this->faker->randomFloat(2, 50, 100),
            'min_amount' => $this->faker->randomFloat(2, 20, 50)
        ];
    }
}
