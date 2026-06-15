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

            // Дополнительные фото: лобби, номера, люкс, ещё один фасад
            $h = $hotel->id;
            $extra = [
                HotelCatalogSeeder::LOBBIES[$h % count(HotelCatalogSeeder::LOBBIES)],
                HotelCatalogSeeder::ROOMS[$h % count(HotelCatalogSeeder::ROOMS)],
                HotelCatalogSeeder::SUITES[$h % count(HotelCatalogSeeder::SUITES)],
                HotelCatalogSeeder::ROOMS[($h + 4) % count(HotelCatalogSeeder::ROOMS)],
                HotelCatalogSeeder::EXTERIORS[($h + 1) % count(HotelCatalogSeeder::EXTERIORS)],
            ];

            foreach ($extra as $id) {
                $images[] = [
                    'image' => HotelCatalogSeeder::pexels($id, 1200),
                    'sort_order' => $order++,
                ];
            }

            $hotel->images()->createMany($images);
        }
    }
}
