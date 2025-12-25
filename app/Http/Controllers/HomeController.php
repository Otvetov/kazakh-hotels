<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display home page
     */
    public function index(Request $request)
    {
        $query = Hotel::query();

        // Search filters
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

        // Get popular cities from database (cities with most hotels)
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

        if ($request->ajax()) {
            // For city search AJAX
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

    /**
     * Get city description
     */
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
}

