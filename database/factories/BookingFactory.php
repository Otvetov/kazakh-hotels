<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'room_id' => Room::factory(),
            'check_in' => now()->addDays(5)->toDateString(),
            'check_out' => now()->addDays(8)->toDateString(),
            'guests' => 1,
            'total_price' => 30000,
            'status' => 'pending',
        ];
    }
}
