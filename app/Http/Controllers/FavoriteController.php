<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{

    public function index()
    {
        $favorites = Auth::user()->favorites()->with('hotel.rooms')->paginate(12);

        return view('favorites.index', compact('favorites'));
    }


    public function toggle(Hotel $hotel)
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

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['is_favorited' => $isFavorited]);
        }

        return back();
    }
}


