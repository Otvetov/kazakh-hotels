<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;

class HotelController extends Controller
{

    public function index(Request $request)
    {
        $query = Hotel::query();

        // Filters
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('min_price')) {
            $query->whereHas('rooms', function ($q) use ($request) {
                $q->where('price_per_night', '>=', $request->min_price);
            });
        }

        if ($request->filled('max_price')) {
            $query->whereHas('rooms', function ($q) use ($request) {
                $q->where('price_per_night', '<=', $request->max_price);
            });
        }

        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->rating);
        }

        // Sorting
        $sort = $request->get('sort', 'popularity');
        switch ($sort) {
            case 'price_asc':
                $query->orderByRaw('(SELECT MIN(price_per_night) FROM rooms WHERE rooms.hotel_id = hotels.id) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('(SELECT MIN(price_per_night) FROM rooms WHERE rooms.hotel_id = hotels.id) DESC');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->latest();
        }

        $hotels = $query->with('rooms')->paginate(12);
        $cities = Hotel::distinct()->pluck('city')->sort();

        // поулярные города
        $popularCities = Hotel::select('city')
            ->selectRaw('COUNT(*) as hotel_count')
            ->groupBy('city')
            ->orderByDesc('hotel_count')
            ->limit(6)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->city,
                    'description' => $this->getCityDescription($item->city),
                ];
            });

        return view('hotels.index', compact('hotels', 'cities', 'popularCities'));
    }

    private function getCityDescription(string $city): string
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

    public function show(Hotel $hotel)
    {
        $hotel->load(['rooms', 'reviews.user']);
        $isFavorited = auth()->check() && $hotel->isFavoritedBy(auth()->id());

        return view('hotels.show', compact('hotel', 'isFavorited'));
    }
}


