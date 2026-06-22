<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminDemoBookingSeeder extends Seeder
{
    /**
     * Завершённое бронирование администратора: срок проживания
     * уже прошёл (дата выезда в прошлом, статус «подтверждено»),
     * поэтому администратор может оставить отзыв об отеле.
     * Идемпотентно: повторный запуск ничего не дублирует.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();

        if (!$admin) {
            return;
        }

        // Уже есть завершённая (прошедшая) бронь — выходим
        $alreadyHas = Booking::where('user_id', $admin->id)
            ->where('status', '!=', 'cancelled')
            ->whereDate('check_out', '<', now()->toDateString())
            ->exists();

        if ($alreadyHas) {
            return;
        }

        $room = Room::inRandomOrder()->first();

        if (!$room) {
            return;
        }

        $checkOut = now()->subDays(3)->startOfDay();
        $checkIn = (clone $checkOut)->subDays(2);
        $nights = 2;

        Booking::create([
            'user_id' => $admin->id,
            'room_id' => $room->id,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'guests' => min(2, $room->capacity),
            'total_price' => $room->price_per_night * $nights,
            'status' => 'confirmed',
        ]);
    }
}
