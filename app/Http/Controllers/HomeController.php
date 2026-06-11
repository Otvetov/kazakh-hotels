<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HasPopularCities;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use HasPopularCities;

    public function index(Request $request)
    {
        $query = Hotel::query();

        // Filters
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('city', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $hotels = $query->with('rooms')->latest()->paginate(12);

        // Популярные города
        $popularCities = $this->popularCities();

        if ($request->ajax()) {
            // ajax
            if ($request->has('ajax') && $request->ajax == 1) {
                $searchTerm = $request->get('city', '');
                $cities = Hotel::select('city')
                    ->distinct()
                    ->where('city', 'like', '%' . $searchTerm . '%')
                    ->limit(10)
                    ->pluck('city')
                    ->toArray();
                
                return response()->json(['cities' => $cities]);
            }

            $html = '';
            foreach ($hotels as $hotel) {
                $html .= view('partials.hotel-card', compact('hotel'))->render();
            }
            return response()->json([
                'html' => $html,
                'has_more' => $hotels->hasMorePages(),
            ]);
        }

        return view('home', compact('hotels', 'popularCities'));
    }
}

