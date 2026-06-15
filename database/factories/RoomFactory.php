<?php

namespace Database\Factories;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    public function definition(): array
    {
        return [
            'hotel_id' => Hotel::factory(),
            'name' => fake()->randomElement(['Standard', 'Deluxe', 'Suite']) . ' Room',
            'price_per_night' => fake()->numberBetween(5000, 50000),
            'capacity' => fake()->numberBetween(1, 4),
            'is_available' => true,
        ];
    }
}
