<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Hotel;
use Illuminate\Support\Collection;

trait HasPopularCities
{
    /**
     * Список популярных городов с краткими описаниями.
     */
    protected function popularCities(): Collection
    {
        return Hotel::select('city')
            ->selectRaw('COUNT(*) as hotel_count')
            ->groupBy('city')
            ->orderByDesc('hotel_count')
            ->limit(6)
            ->get()
            ->map(fn ($item) => [
                'name' => $item->city,
                'description' => $this->cityDescription($item->city),
            ]);
    }

    /**
     * Краткое описание города.
     */
    protected function cityDescription(string $city): string
    {
        $descriptions = [
            'Алматы' => 'Крупнейший город Казахстана',
            'Астана' => 'Столица Казахстана',
            'Шымкент' => 'Южная столица Казахстана',
            'Караганда' => 'Промышленный центр',
            'Актобе' => 'Западный регион',
            'Тараз' => 'Древний город',
            'Павлодар' => 'Северный регион',
            'Усть-Каменогорск' => 'Восточный регион',
        ];

        return $descriptions[$city] ?? 'Популярное направление';
    }
}
