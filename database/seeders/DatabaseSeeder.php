<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Favorite;
use App\Models\Hotel;
use App\Models\Review;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create regular users
        $users = User::factory(10)->create();

        // Create hotels
        $cities = ['Алматы', 'Астана', 'Шымкент', 'Караганда', 'Актобе', 'Тараз', 'Павлодар', 'Усть-Каменогорск'];
        
        $hotels = [];
        foreach ($cities as $city) {
            for ($i = 0; $i < 3; $i++) {
                $hotels[] = Hotel::create([
                    'name' => fake()->company() . ' Hotel',
                    'city' => $city,
                    'address' => fake()->streetAddress(),
                    'description' => fake()->paragraph(3),
                    'rating' => fake()->randomFloat(1, 3, 5),
                ]);
            }
        }

        // Create rooms for each hotel
        foreach ($hotels as $hotel) {
            $roomCount = fake()->numberBetween(3, 8);
            for ($i = 0; $i < $roomCount; $i++) {
                Room::create([
                    'hotel_id' => $hotel->id,
                    'name' => fake()->randomElement(['Standard', 'Deluxe', 'Suite', 'Executive', 'Presidential']) . ' Room',
                    'price_per_night' => fake()->numberBetween(5000, 50000),
                    'capacity' => fake()->numberBetween(1, 4),
                    'is_available' => fake()->boolean(80),
                ]);
            }
        }

        // Create bookings
        foreach ($users->take(5) as $user) {
            $room = Room::inRandomOrder()->first();
            Booking::create([
                'user_id' => $user->id,
                'room_id' => $room->id,
                'check_in' => now()->addDays(fake()->numberBetween(1, 30)),
                'check_out' => now()->addDays(fake()->numberBetween(31, 60)),
                'guests' => fake()->numberBetween(1, $room->capacity),
                'total_price' => $room->price_per_night * fake()->numberBetween(1, 7),
                'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled']),
            ]);
        }

        // Create favorites
        foreach ($users->take(5) as $user) {
            Favorite::create([
                'user_id' => $user->id,
                'hotel_id' => $hotels[array_rand($hotels)]->id,
            ]);
        }

        // Create reviews
        foreach ($users->take(8) as $user) {
            Review::create([
                'user_id' => $user->id,
                'hotel_id' => $hotels[array_rand($hotels)]->id,
                'rating' => fake()->numberBetween(1, 5),
                'comment' => fake()->paragraph(2),
                'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
            ]);
        }
    }
}
