<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hotel>
 */
class HotelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' Hotel',
            'city' => fake()->randomElement(['Алматы', 'Астана', 'Шымкент', 'Караганда']),
            'address' => fake()->streetAddress(),
            'description' => fake()->paragraph(),
            'rating' => fake()->randomFloat(1, 3, 5),
            'image' => null,
        ];
    }
}
