<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    /**
     * Display hotels catalog
     */
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

        return view('hotels.index', compact('hotels', 'cities'));
    }

    /**
     * Display hotel details
     */
    public function show(Hotel $hotel)
    {
        $hotel->load(['rooms', 'reviews.user']);
        $isFavorited = auth()->check() && $hotel->isFavoritedBy(auth()->id());

        return view('hotels.show', compact('hotel', 'isFavorited'));
    }
}

