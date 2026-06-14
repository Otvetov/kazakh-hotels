<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelCatalogSeeder extends Seeder
{
    /**
     * Подобранные бесплатные фото (Pexels, лицензия Pexels License) по категориям.
     */
    public const EXTERIORS = [28238364, 10613691, 6066400, 2394446, 8957310, 16094034,
        30097235, 18426838, 30726924, 12041472, 11783047];

    public const ROOMS = [34496701, 7745929, 14750394, 18801062, 7722164, 237371,
        5860693, 27638174, 4493299, 7746080, 10415975];

    public const LOBBIES = [7512139, 28102352, 7820327, 695193, 5378693, 2869215, 7820734];

    public const SUITES = [6466496, 6467621, 2725675, 16635688, 164595, 3688261,
        5379181, 271639, 279805, 8082217];

    /**
     * Собирает URL изображения Pexels по id.
     */
    public static function pexels(int $id, int $w = 1200): string
    {
        return "https://images.pexels.com/photos/{$id}/pexels-photo-{$id}.jpeg?auto=compress&cs=tinysrgb&w={$w}";
    }

    /**
     * Наполняет каталог реальными отелями Казахстана.
     * Названия и адреса — публичная справочная информация; фотографии —
     * иллюстративные из бесплатного банка Pexels (не принадлежат отелям).
     */
    public function run(): void
    {
        foreach ($this->catalog() as $city => $cfg) {
            foreach ($cfg['hotels'] as [$name, $address, $tier]) {
                $hotel = Hotel::create([
                    'name' => $name,
                    'city' => $city,
                    'address' => $address,
                    'description' => $this->description($city, $tier),
                    'rating' => $this->rating($tier),
                    'image' => self::pexels(self::EXTERIORS[crc32($name) % count(self::EXTERIORS)], 1200),
                ]);

                $this->seedRooms($hotel, $tier, $cfg['factor']);
            }
        }
    }

    /**
     * Реальные отели по городам: ценовой коэффициент и список [название, адрес, класс].
     */
    private function catalog(): array
    {
        return [
            'Алматы' => ['factor' => 1.00, 'hotels' => [
                ['Rixos Almaty', 'проспект Сейфуллина, 506', 'premium'],
                ['The Ritz-Carlton, Almaty', 'проспект Аль-Фараби, 77/7', 'premium'],
                ['InterContinental Almaty', 'улица Желтоксан, 181', 'premium'],
                ['Rahat Palace Hotel', 'проспект Сатпаева, 29/6', 'premium'],
                ['Dostyk Hotel', 'улица Курмангазы, 36', 'premium'],
                ['Hotel Kazakhstan', 'проспект Достык, 52', 'standard'],
                ['Almaty Hotel', 'проспект Кабанбай батыра, 85', 'standard'],
            ]],
            'Астана' => ['factor' => 1.00, 'hotels' => [
                ['Rixos President Astana', 'улица Кунаева, 7', 'premium'],
                ['The St. Regis Astana', 'проспект Кабанбай батыра, 1', 'premium'],
                ['The Ritz-Carlton, Astana', 'проспект Достык, 16', 'premium'],
                ['Hilton Astana', 'проспект Кабанбай батыра, 14/1', 'premium'],
                ['Hilton Garden Inn Astana', 'проспект Кабанбай батыра, 15', 'standard'],
                ['Ramada by Wyndham Astana', 'улица Бейбитшилик, 8', 'standard'],
            ]],
            'Шымкент' => ['factor' => 0.80, 'hotels' => [
                ['Rixos Khadisha Shymkent', 'улица Желтоксан, 17', 'premium'],
                ['Sapar Hotel', 'проспект Тауке хана, 9', 'standard'],
                ['Diplomat Hotel Shymkent', 'улица Казыбек би, 24', 'standard'],
            ]],
            'Караганда' => ['factor' => 0.75, 'hotels' => [
                ['Cosmonaut Hotel', 'улица Аманжолова, 162а', 'premium'],
                ['Dedeman Park Hotel Karaganda', 'проспект Бухар жырау, 50', 'premium'],
                ['Chayka Hotel', 'улица Ерубаева, 27', 'standard'],
            ]],
            'Актау' => ['factor' => 0.95, 'hotels' => [
                ['Rixos Water World Aktau', 'База отдыха Тёплый пляж, 34', 'premium'],
                ['Caspian Riviera Grand Palace', '4-й микрорайон, 1', 'premium'],
                ['Renaissance Aktau Hotel', '9-й микрорайон, 1', 'premium'],
                ['Grand Nur Plaza', 'микрорайон 29А, 5/13', 'premium'],
            ]],
            'Атырау' => ['factor' => 0.95, 'hotels' => [
                ['Renaissance Atyrau Hotel', 'улица Сатпаева, 15Б', 'premium'],
                ['River Palace Hotel', 'улица Айтеке би, 55', 'premium'],
                ['Ak Zhaik Hotel', 'проспект Азаттык, 24', 'standard'],
            ]],
            'Туркестан' => ['factor' => 0.80, 'hotels' => [
                ['Rixos Turkistan', 'проспект Бекзата Саттарханова, 33а', 'premium'],
                ['Karavansaray Turkistan', 'проспект Бекзата Саттарханова, 25а', 'premium'],
                ['Hanaka Hotel', 'улица Байбурт, 3А', 'standard'],
            ]],
            'Бурабай' => ['factor' => 0.95, 'hotels' => [
                ['Rixos Borovoe', 'озеро Щучье, юго-восточный берег, 50', 'premium'],
                ['Cronwell Resort Borovoe', 'улица Кенесары, 1', 'premium'],
            ]],
            'Павлодар' => ['factor' => 0.70, 'hotels' => [
                ['Hotel Saryarka', 'улица Торайгырова, 1', 'standard'],
                ['Irtysh Hotel', 'улица Бектурова, 79', 'standard'],
            ]],
            'Усть-Каменогорск' => ['factor' => 0.70, 'hotels' => [
                ['Hotel Ust-Kamenogorsk', 'улица Кабанбай батыра, 158', 'standard'],
                ['Shangri-La Oskemen', 'улица Пермитина, 11/1', 'standard'],
            ]],
            'Семей' => ['factor' => 0.65, 'hotels' => [
                ['NomAD Hotel', 'улица Ибраева, 149', 'standard'],
                ['Binom Hotel', 'улица Шакарима, 20', 'standard'],
            ]],
            'Кызылорда' => ['factor' => 0.65, 'hotels' => [
                ['Bayterek Hotel', 'улица Панфилова, 72', 'standard'],
                ['Akmeshit Hotel', 'проспект Абая, 27', 'standard'],
            ]],
            'Костанай' => ['factor' => 0.65, 'hotels' => [
                ['Medeu Hotel', 'улица Баймагамбетова, 166а', 'premium'],
                ['Tobol Hotel', 'улица 5 Апреля, 64', 'standard'],
            ]],
            'Петропавловск' => ['factor' => 0.65, 'hotels' => [
                ['Skif Hotel & SPA', 'улица Парковая, 118', 'premium'],
                ['Kyzyl Zhar Hotel', 'улица Конституции Казахстана, 54', 'standard'],
            ]],
            'Уральск' => ['factor' => 0.70, 'hotels' => [
                ['Pushkin Hotel', 'проспект Нурсултана Назарбаева, 148Б', 'standard'],
            ]],
            'Тараз' => ['factor' => 0.65, 'hotels' => [
                ['Zhambyl Hotel', 'проспект Толе би, 42', 'standard'],
            ]],
            'Актобе' => ['factor' => 0.75, 'hotels' => [
                ['Ilek Hotel', 'улица Айтеке би, 44', 'standard'],
            ]],
            'Кокшетау' => ['factor' => 0.65, 'hotels' => [
                ['Dostyq Hotel', 'улица Абая, 89', 'standard'],
            ]],
            'Талдыкорган' => ['factor' => 0.65, 'hotels' => [
                ['Aiser Hotel', 'улица Акын Сара, 128', 'standard'],
            ]],
            'Темиртау' => ['factor' => 0.60, 'hotels' => [
                ['Hotel Temirtau', 'проспект Республики, 1/2', 'standard'],
            ]],
        ];
    }

    /**
     * Нейтральное описание (без утверждений о конкретных удобствах).
     */
    private function description(string $city, string $tier): string
    {
        if ($tier === 'premium') {
            return "Отель высокого уровня в городе {$city}. Просторные номера, ресторан и "
                . 'современный сервис — удобно как для отдыха, так и для деловых поездок.';
        }

        return "Комфортабельный отель в городе {$city}. Удобное расположение и уютные "
            . 'номера для гостей города.';
    }

    private function rating(string $tier): float
    {
        return match ($tier) {
            'premium' => fake()->randomFloat(1, 4.3, 4.9),
            default => fake()->randomFloat(1, 3.9, 4.6),
        };
    }

    /**
     * Создаёт набор номеров под класс отеля с ценами по городу.
     */
    private function seedRooms(Hotel $hotel, string $tier, float $factor): void
    {
        // [название, базовая цена ₸, вместимость]
        $catalog = [
            'Стандарт'       => [15000, 2],
            'Комфорт'        => [22000, 3],
            'Полулюкс'       => [32000, 3],
            'Люкс'           => [48000, 4],
            'Президентский'  => [95000, 5],
        ];

        $types = $tier === 'premium'
            ? ['Комфорт', 'Полулюкс', 'Люкс', 'Президентский']
            : ['Стандарт', 'Комфорт', 'Полулюкс'];

        $luxTypes = ['Полулюкс', 'Люкс', 'Президентский'];

        foreach ($types as $idx => $type) {
            [$base, $capacity] = $catalog[$type];
            $price = round($base * $factor / 500) * 500;

            $pool = in_array($type, $luxTypes, true) ? self::SUITES : self::ROOMS;
            $imageId = $pool[($hotel->id + $idx) % count($pool)];

            $hotel->rooms()->create([
                'name' => $type,
                'image' => self::pexels($imageId, 1000),
                'price_per_night' => $price,
                'capacity' => $capacity,
                'is_available' => fake()->boolean(85),
            ]);
        }
    }
}
