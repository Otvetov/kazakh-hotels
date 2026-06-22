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
        // Администратор
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Обычные пользователи
        $users = User::factory(10)->create();

        // Каталог отелей по городам Казахстана + номера
        $this->call(HotelCatalogSeeder::class);

        $hotels = Hotel::all();

        // Бронирования
        foreach ($users->take(6) as $user) {
            $room = Room::inRandomOrder()->first();
            $nights = fake()->numberBetween(1, 7);
            $checkIn = now()->addDays(fake()->numberBetween(1, 30));

            Booking::create([
                'user_id' => $user->id,
                'room_id' => $room->id,
                'check_in' => $checkIn,
                'check_out' => (clone $checkIn)->addDays($nights),
                'guests' => fake()->numberBetween(1, $room->capacity),
                'total_price' => $room->price_per_night * $nights,
                'status' => fake()->randomElement(['pending', 'confirmed', 'confirmed', 'cancelled']),
            ]);
        }

        // Избранное
        foreach ($users->take(6) as $user) {
            Favorite::firstOrCreate([
                'user_id' => $user->id,
                'hotel_id' => $hotels->random()->id,
            ]);
        }

        // Отзывы с реальными русскими комментариями
        $comments = [
            'Отличный отель, чисто и уютно. Персонал вежливый, заселили быстро.',
            'Хорошее расположение, рядом много кафе. Завтрак вкусный, рекомендую.',
            'Номер просторный, кровать удобная. Wi-Fi работал без нареканий.',
            'Всё понравилось, кроме шумной улицы по ночам. В остальном на высоте.',
            'Приятный отель за свои деньги. Вернёмся ещё раз.',
            'Сервис на уровне, помогли с трансфером. Спасибо за гостеприимство!',
            'Чистые номера, приветливый персонал. Соотношение цены и качества хорошее.',
            'Останавливались по работе — удобно, есть конференц-зал и быстрый интернет.',
        ];

        foreach ($hotels->random(min(40, $hotels->count())) as $hotel) {
            $count = fake()->numberBetween(1, 3);
            for ($i = 0; $i < $count; $i++) {
                Review::create([
                    'user_id' => $users->random()->id,
                    'hotel_id' => $hotel->id,
                    'rating' => fake()->numberBetween(3, 5),
                    'comment' => fake()->randomElement($comments),
                    'status' => fake()->randomElement(['approved', 'approved', 'approved', 'pending']),
                ]);
            }
        }

        // Галерея фотографий для всех отелей
        $this->call(HotelImageSeeder::class);

        // Завершённая демо-бронь администратора (можно оставить отзыв)
        $this->call(AdminDemoBookingSeeder::class);
    }
}
