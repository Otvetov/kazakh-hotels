<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display booking form
     */
    public function create(Request $request)
    {
        if (!$request->filled('room_id')) {
            return redirect()->route('hotels.index')->withErrors(['error' => 'Please select a room first.']);
        }

        $room = Room::with('hotel')->findOrFail($request->room_id);
        $checkIn = $request->get('check_in');
        $checkOut = $request->get('check_out');
        $guests = $request->get('guests', $room->capacity);

        // If dates are not provided, redirect to hotel page
        if (!$checkIn || !$checkOut) {
            return redirect()->route('hotels.show', $room->hotel->id)
                ->with('info', 'Please select check-in and check-out dates to proceed with booking.')
                ->withInput(['room_id' => $room->id]);
        }

        // Calculate nights and total price
        $checkInDate = \Carbon\Carbon::parse($checkIn);
        $checkOutDate = \Carbon\Carbon::parse($checkOut);
        $nights = $checkInDate->diffInDays($checkOutDate);
        $totalPrice = $room->price_per_night * $nights;

        return view('bookings.create', compact('room', 'checkIn', 'checkOut', 'guests', 'nights', 'totalPrice'));
    }

    /**
     * Store booking
     */
    public function store(StoreBookingRequest $request)
    {
        try {
            $room = Room::with('hotel')->findOrFail($request->room_id);

            // Check availability
            if (!$room->isAvailableForDates($request->check_in, $request->check_out)) {
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'Номер недоступен на выбранные даты. Пожалуйста, выберите другие даты.']);
            }

            // Calculate total price
            $checkIn = \Carbon\Carbon::parse($request->check_in);
            $checkOut = \Carbon\Carbon::parse($request->check_out);
            $nights = $checkIn->diffInDays($checkOut);
            $totalPrice = $room->price_per_night * $nights;

            $booking = Booking::create([
                'user_id' => Auth::id(),
                'room_id' => $room->id,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'guests' => $request->guests,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Бронирование успешно создано!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Произошла ошибка при создании бронирования: ' . $e->getMessage()]);
        }
    }

    /**
     * Display booking details
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        $booking->load(['room.hotel', 'user']);

        return view('bookings.show', compact('booking'));
    }

    /**
     * Display user bookings
     */
    public function index(Request $request)
    {
        $query = Auth::user()->bookings()->with(['room.hotel']);

        $tab = $request->get('tab', 'active');
        switch ($tab) {
            case 'past':
                $query->where('check_out', '<', now());
                break;
            case 'cancelled':
                $query->where('status', 'cancelled');
                break;
            default:
                $query->where('check_out', '>=', now())->where('status', '!=', 'cancelled');
        }

        $bookings = $query->latest()->paginate(10);

        return view('bookings.index', compact('bookings', 'tab'));
    }

    /**
     * Cancel booking
     */
    public function cancel(Booking $booking)
    {
        $this->authorize('update', $booking);

        if ($booking->status === 'cancelled') {
            return back()->withErrors(['error' => 'Booking is already cancelled.']);
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking cancelled successfully.');
    }
}

