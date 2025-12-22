<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Get user favorites
     */
    public function index(): JsonResponse
    {
        $favorites = Auth::user()->favorites()->with('hotel.rooms')->get();

        return response()->json([
            'data' => $favorites->map(function ($favorite) {
                $hotel = $favorite->hotel;
                return [
                    'id' => $favorite->id,
                    'hotel' => [
                        'id' => $hotel->id,
                        'name' => $hotel->name,
                        'city' => $hotel->city,
                        'address' => $hotel->address,
                        'rating' => $hotel->rating,
                        'image' => $hotel->image ? asset('storage/' . $hotel->image) : null,
                        'min_price' => $hotel->min_price,
                    ],
                ];
            }),
        ]);
    }

    /**
     * Toggle favorite
     */
    public function toggle(Hotel $hotel): JsonResponse
    {
        $favorite = Favorite::where('user_id', Auth::id())
            ->where('hotel_id', $hotel->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $isFavorited = false;
        } else {
            Favorite::create([
                'user_id' => Auth::id(),
                'hotel_id' => $hotel->id,
            ]);
            $isFavorited = true;
        }

        return response()->json([
            'message' => $isFavorited ? 'Hotel added to favorites' : 'Hotel removed from favorites',
            'is_favorited' => $isFavorited,
        ]);
    }

    /**
     * Remove favorite
     */
    public function remove(Hotel $hotel): JsonResponse
    {
        $favorite = Favorite::where('user_id', Auth::id())
            ->where('hotel_id', $hotel->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'message' => 'Hotel removed from favorites',
            ]);
        }

        return response()->json([
            'message' => 'Hotel is not in favorites',
        ], 404);
    }
}

