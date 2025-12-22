<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Review;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display admin dashboard
     */
    public function index()
    {
        $stats = [
            'total_bookings' => Booking::count(),
            'total_revenue' => Booking::where('status', 'confirmed')->sum('total_price'),
            'pending_reviews' => Review::where('status', 'pending')->count(),
            'total_hotels' => Hotel::count(),
            'total_users' => User::count(),
            'top_cities' => Hotel::selectRaw('city, COUNT(*) as count')
                ->groupBy('city')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get(),
        ];

        return view('admin.index', compact('stats'));
    }
}

