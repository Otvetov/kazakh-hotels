<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    /**
     * Get hotels list
     */
    public function index(Request $request): JsonResponse
    {
        $query = Hotel::query();

        // Filters
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('city', 'like', '%' . $request->search . '%');
            });
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

        $hotels = $query->with('rooms')->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => $hotels->items(),
            'meta' => [
                'current_page' => $hotels->currentPage(),
                'last_page' => $hotels->lastPage(),
                'per_page' => $hotels->perPage(),
                'total' => $hotels->total(),
            ],
        ]);
    }

    /**
     * Get hotel details
     */
    public function show(Hotel $hotel): JsonResponse
    {
        $hotel->load(['rooms', 'reviews.user']);
        $isFavorited = auth()->check() && $hotel->isFavoritedBy(auth()->id());

        return response()->json([
            'data' => [
                'id' => $hotel->id,
                'name' => $hotel->name,
                'city' => $hotel->city,
                'address' => $hotel->address,
                'description' => $hotel->description,
                'rating' => $hotel->rating,
                'image' => $hotel->image ? asset('storage/' . $hotel->image) : null,
                'min_price' => $hotel->min_price,
                'is_favorited' => $isFavorited,
                'rooms' => $hotel->rooms->map(function ($room) {
                    return [
                        'id' => $room->id,
                        'name' => $room->name,
                        'price_per_night' => $room->price_per_night,
                        'capacity' => $room->capacity,
                        'is_available' => $room->is_available,
                    ];
                }),
                'reviews' => $hotel->reviews->map(function ($review) {
                    return [
                        'id' => $review->id,
                        'user_name' => $review->user->name,
                        'rating' => $review->rating,
                        'comment' => $review->comment,
                        'created_at' => $review->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
            ],
        ]);
    }

    /**
     * Get hotel rooms
     */
    public function rooms(Hotel $hotel, Request $request): JsonResponse
    {
        $query = $hotel->rooms();

        if ($request->filled('check_in') && $request->filled('check_out')) {
            $rooms = $hotel->rooms->filter(function ($room) use ($request) {
                return $room->isAvailableForDates($request->check_in, $request->check_out);
            });
        } else {
            $rooms = $query->get();
        }

        return response()->json([
            'data' => $rooms->map(function ($room) {
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'price_per_night' => $room->price_per_night,
                    'capacity' => $room->capacity,
                    'is_available' => $room->is_available,
                ];
            }),
        ]);
    }
}

