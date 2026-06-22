<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HasPopularCities;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    use HasPopularCities;

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

        $hotels = $query->with('rooms')->paginate(12)->withQueryString();
        $cities = Hotel::distinct()->pluck('city')->sort();

        // Диапазон цен для подсказок в фильтре
        $priceMin = (int) Room::min('price_per_night');
        $priceMax = (int) Room::max('price_per_night');

        // Популярные города
        $popularCities = $this->popularCities();

        return view('hotels.index', compact('hotels', 'cities', 'popularCities', 'priceMin', 'priceMax'));
    }

    public function show(Hotel $hotel)
    {
        $hotel->load(['rooms', 'reviews.user', 'images']);
        $isFavorited = auth()->check() && $hotel->isFavoritedBy(auth()->id());

        // Отзыв доступен только гостю, прожившему полный срок
        $canReview = auth()->check() && $hotel->hasCompletedStayBy(auth()->id());

        return view('hotels.show', compact('hotel', 'isFavorited', 'canReview'));
    }
}


