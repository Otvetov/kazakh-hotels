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

        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.hotel-cards', compact('hotels'))->render(),
                'has_more' => $hotels->hasMorePages(),
            ]);
        }

        return view('home', compact('hotels'));
    }
}

