<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\HotelImage;
use Illuminate\Database\Seeder;

class HotelImageSeeder extends Seeder
{
    /**
     * Наполняет галерею отелей изображениями (для уже существующих отелей).
     */
    public function run(): void
    {
        foreach (Hotel::all() as $hotel) {
            if ($hotel->images()->exists()) {
                continue;
            }

            $images = [];
            $order = 0;

            // Основное изображение отеля делаем первым в галерее
            if ($hotel->image) {
                $images[] = ['image' => $hotel->image, 'sort_order' => $order++];
            }

            // Несколько дополнительных фото
            for ($i = 0; $i < 5; $i++) {
                $images[] = [
                    'image' => 'https://picsum.photos/seed/hotel' . $hotel->id . '_' . $i . '/1200/800',
                    'sort_order' => $order++,
                ];
            }

            $hotel->images()->createMany($images);
        }
    }
}
