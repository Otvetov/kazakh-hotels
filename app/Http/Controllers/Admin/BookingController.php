<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Отобразить список бронирований
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'room.hotel']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Обновить статус бронирования
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => ['required', 'in:pending,confirmed,cancelled'],
        ]);

        $booking->update(['status' => $request->status]);

        return back()->with('success', 'Статус бронирования успешно обновлен.');
    }
}


