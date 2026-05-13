<?php

namespace Database\Factories;

use App\Enums\FileTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'src' => 'default/default.jpg',
            'path' => 'default/',
            'type' => FileTypes::IMAGE->value,
        ];
    }
}
