<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelCatalogSeeder extends Seeder
{
    /**
     * Наполняет каталог реалистичными отелями по городам Казахстана.
     * Данные собственные (не из сторонних сервисов), чтобы их можно было
     * свободно использовать и объяснить на защите.
     */
    public function run(): void
    {
        $usedNames = [];

        foreach ($this->cities() as $city => $cfg) {
            for ($i = 0; $i < $cfg['count']; $i++) {
                $name = $this->uniqueName($city, $usedNames);
                $tier = $this->pickTier();

                $hotel = Hotel::create([
                    'name' => $name,
                    'city' => $city,
                    'address' => $this->address($cfg['streets']),
                    'description' => $this->description($city),
                    'rating' => $this->rating($tier),
                    'image' => 'https://loremflickr.com/1200/600/hotel,building?lock=' . (crc32($name) % 100000),
                ]);

                $this->seedRooms($hotel, $tier, $cfg['price_factor']);
            }
        }
    }

    /**
     * Города Казахстана: количество отелей, ценовой коэффициент и улицы.
     */
    private function cities(): array
    {
        $common = ['проспект Абая', 'улица Достық', 'проспект Назарбаева', 'улица Толе би',
            'проспект Республики', 'улица Кунаева', 'улица Сатпаева', 'улица Желтоқсан',
            'улица Бөгенбай батыра', 'проспект Тәуелсіздік'];

        return [
            'Алматы'              => ['count' => 8, 'price_factor' => 1.00, 'streets' => $common],
            'Астана'              => ['count' => 7, 'price_factor' => 1.00, 'streets' => $common],
            'Шымкент'             => ['count' => 5, 'price_factor' => 0.75, 'streets' => $common],
            'Караганда'           => ['count' => 4, 'price_factor' => 0.70, 'streets' => $common],
            'Актау'               => ['count' => 4, 'price_factor' => 0.90, 'streets' => ['микрорайон 3', 'микрорайон 14', 'набережная', 'микрорайон 11']],
            'Туркестан'           => ['count' => 4, 'price_factor' => 0.70, 'streets' => ['улица Тәуке хана', 'проспект Тәуке хана', 'улица Қожа Ахмет Ясауи', 'улица Айтеке би']],
            'Бурабай'             => ['count' => 4, 'price_factor' => 0.90, 'streets' => ['улица Кенесары', 'озеро Боровое', 'улица Абылай хана', 'курортная зона']],
            'Атырау'              => ['count' => 3, 'price_factor' => 0.90, 'streets' => ['проспект Азаттық', 'улица Сатпаева', 'проспект Абулхаир хана']],
            'Актобе'              => ['count' => 3, 'price_factor' => 0.70, 'streets' => $common],
            'Тараз'               => ['count' => 3, 'price_factor' => 0.65, 'streets' => $common],
            'Павлодар'            => ['count' => 3, 'price_factor' => 0.65, 'streets' => $common],
            'Усть-Каменогорск'    => ['count' => 3, 'price_factor' => 0.65, 'streets' => $common],
            'Семей'               => ['count' => 3, 'price_factor' => 0.60, 'streets' => $common],
            'Кызылорда'           => ['count' => 3, 'price_factor' => 0.60, 'streets' => $common],
            'Костанай'            => ['count' => 3, 'price_factor' => 0.60, 'streets' => $common],
            'Петропавловск'       => ['count' => 3, 'price_factor' => 0.60, 'streets' => $common],
            'Уральск'             => ['count' => 3, 'price_factor' => 0.65, 'streets' => $common],
            'Кокшетау'            => ['count' => 2, 'price_factor' => 0.60, 'streets' => $common],
            'Талдыкорган'         => ['count' => 2, 'price_factor' => 0.60, 'streets' => $common],
            'Темиртау'            => ['count' => 2, 'price_factor' => 0.60, 'streets' => $common],
        ];
    }

    /**
     * Генерирует уникальное правдоподобное название отеля.
     */
    private function uniqueName(string $city, array &$used): string
    {
        $brands = ['Grand', 'Royal', 'Plaza', 'Premier', 'City', 'Comfort', 'Astoria',
            'Riviera', 'Continental', 'Imperial', 'Palace', 'Aurora', 'Nomad', 'Caspian',
            'Алтын Орда', 'Жібек Жолы', 'Достық', 'Самал', 'Керуен', 'Тұран', 'Шаңырақ',
            'Ордабасы', 'Алатау', 'Сарыарқа', 'Тұмар', 'Ақ Бота', 'Жайлау', 'Көктем'];

        $patterns = [
            fn ($b, $c) => "{$b} Hotel",
            fn ($b, $c) => "{$b} {$c}",
            fn ($b, $c) => "Отель «{$b}»",
            fn ($b, $c) => "{$b} Plaza",
        ];

        do {
            $brand = $brands[array_rand($brands)];
            $pattern = $patterns[array_rand($patterns)];
            $name = $pattern($brand, $city);
        } while (isset($used[$name]));

        $used[$name] = true;

        return $name;
    }

    private function address(array $streets): string
    {
        return $streets[array_rand($streets)] . ', ' . fake()->numberBetween(1, 120);
    }

    /**
     * Собирает естественное русскоязычное описание отеля.
     */
    private function description(string $city): string
    {
        $intro = ['Современный отель', 'Уютная гостиница', 'Комфортабельный отель',
            'Стильный отель бизнес-класса', 'Семейный отель', 'Новый отель'];

        $location = [
            "в центре города {$city}",
            "в деловом районе города {$city}",
            "недалеко от главных достопримечательностей {$city}",
            "в тихом районе {$city}",
            "рядом с центральной площадью {$city}",
        ];

        $amenities = [
            'К услугам гостей бесплатный Wi-Fi, завтрак «шведский стол» и круглосуточная стойка регистрации.',
            'В отеле есть ресторан, фитнес-зал и бесплатная парковка.',
            'Конференц-зал, ресторан и room-service делают отель удобным для деловых поездок.',
            'Просторные номера, бесплатный Wi-Fi и охраняемая парковка.',
            'Кафе, прачечная и трансфер от аэропорта по запросу.',
        ];

        return $intro[array_rand($intro)] . ' ' . $location[array_rand($location)] . '. '
            . $amenities[array_rand($amenities)];
    }

    private function pickTier(): string
    {
        return ['budget', 'standard', 'standard', 'premium'][array_rand([0, 1, 2, 3])];
    }

    private function rating(string $tier): float
    {
        return match ($tier) {
            'premium' => fake()->randomFloat(1, 4.3, 4.9),
            'standard' => fake()->randomFloat(1, 3.9, 4.6),
            default => fake()->randomFloat(1, 3.4, 4.3),
        };
    }

    /**
     * Создаёт набор номеров под уровень отеля с ценами по городу.
     */
    private function seedRooms(Hotel $hotel, string $tier, float $factor): void
    {
        // [название, базовая цена ₸, вместимость]
        $catalog = [
            'Эконом'         => [9000, 2],
            'Стандарт'       => [15000, 2],
            'Комфорт'        => [22000, 3],
            'Полулюкс'       => [32000, 3],
            'Люкс'           => [48000, 4],
            'Президентский'  => [95000, 5],
        ];

        $types = match ($tier) {
            'premium' => ['Комфорт', 'Полулюкс', 'Люкс', 'Президентский'],
            'standard' => ['Стандарт', 'Комфорт', 'Полулюкс'],
            default => ['Эконом', 'Стандарт', 'Комфорт'],
        };

        foreach ($types as $type) {
            [$base, $capacity] = $catalog[$type];
            $price = round($base * $factor / 500) * 500;

            $hotel->rooms()->create([
                'name' => $type,
                'price_per_night' => $price,
                'capacity' => $capacity,
                'is_available' => fake()->boolean(85),
            ]);
        }
    }
}
