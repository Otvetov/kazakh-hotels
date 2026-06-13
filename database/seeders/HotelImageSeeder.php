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

            // Несколько дополнительных фото (интерьеры, номера, лобби)
            $tags = ['hotel,room', 'hotel,lobby', 'bedroom', 'resort,pool', 'hotel,bathroom'];
            foreach ($tags as $i => $tag) {
                $images[] = [
                    'image' => 'https://loremflickr.com/1200/800/' . $tag . '?lock=' . ($hotel->id * 10 + $i),
                    'sort_order' => $order++,
                ];
            }

            $hotel->images()->createMany($images);
        }
    }
}
