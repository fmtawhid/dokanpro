<?php

namespace Database\Factories;

use App\Repositories\CustomerGroupRepository;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customerGroup = $this->faker->randomElement(CustomerGroupRepository::getAll());
        return [
            'shop_id' => 1,
            'created_by' => 2,
            'customer_group_id' => $customerGroup->id,
            'name' => $this->faker->name,
            'company_name' => $this->faker->company,
            'email' => $this->faker->email,
            'phone_number' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'deposit' => $this->faker->randomFloat('2', 0, 2),
            'expense' => $this->faker->randomFloat('2', 0, 2),
        ];
    }
}
