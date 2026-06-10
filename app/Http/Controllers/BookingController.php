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
     * Проверка доступности номера на выбранные даты (AJAX).
     */
    public function availability(Room $room, Request $request)
    {
        $checkIn = $request->get('check_in');
        $checkOut = $request->get('check_out');

        if (!$checkIn || !$checkOut) {
            return response()->json(['available' => false, 'reason' => 'no_dates']);
        }

        if (!$room->is_available) {
            return response()->json(['available' => false, 'reason' => 'disabled']);
        }

        // Бронирования, пересекающиеся с выбранными датами
        $conflicting = $room->bookings()
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in', '<=', $checkIn)
                          ->where('check_out', '>=', $checkOut);
                    });
            })
            ->get();

        if ($conflicting->isEmpty()) {
            return response()->json(['available' => true]);
        }

        // Дата, до которой номер занят (последняя дата выезда среди пересечений)
        $busyUntil = $conflicting->max('check_out');

        return response()->json([
            'available' => false,
            'reason' => 'booked',
            'busy_until' => $busyUntil ? $busyUntil->format('d.m.Y') : null,
        ]);
    }

    public function create(Request $request)
    {
        if (!$request->filled('room_id')) {
            return redirect()->route('hotels.index')->withErrors(['error' => 'Please select a room first.']);
        }

        $room = Room::with('hotel')->findOrFail($request->room_id);
        $checkIn = $request->get('check_in');
        $checkOut = $request->get('check_out');
        $guests = $request->get('guests', $room->capacity);

        // Если даты не указаны, перенаправьте на страницу отеля
        if (!$checkIn || !$checkOut) {
            return redirect()->route('hotels.show', $room->hotel->id)
                ->with('info', 'Пожалуйста, выберите даты заезда и выезда.')
                ->withInput(['room_id' => $room->id]);
        }

        // Проверяем доступность ещё до страницы оформления,
        // чтобы пользователь не узнавал о занятости на чекауте
        if (!$room->isAvailableForDates($checkIn, $checkOut)) {
            return redirect()->route('hotels.show', $room->hotel->id)
                ->with('info', 'К сожалению, этот номер уже занят на выбранные даты. Пожалуйста, выберите другие даты.');
        }

        // Вычислить количество ночей и общую цену
        $checkInDate = \Carbon\Carbon::parse($checkIn);
        $checkOutDate = \Carbon\Carbon::parse($checkOut);
        $nights = $checkInDate->diffInDays($checkOutDate);
        $totalPrice = $room->price_per_night * $nights;

        return view('bookings.create', compact('room', 'checkIn', 'checkOut', 'guests', 'nights', 'totalPrice'));
    }


    public function store(StoreBookingRequest $request)
    {
        try {
            $room = Room::with('hotel')->findOrFail($request->room_id);

            // Проверить доступность номера на выбранные даты.
            // Если занято — возвращаем на страницу отеля, а не на чекаут.
            if (!$room->isAvailableForDates($request->check_in, $request->check_out)) {
                return redirect()->route('hotels.show', $room->hotel->id)
                    ->with('info', 'К сожалению, этот номер уже занят на выбранные даты. Пожалуйста, выберите другие даты.');
            }

            // Вычислить общую цену
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
     * Show booking details
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        $booking->load(['room.hotel', 'user']);

        return view('bookings.show', compact('booking'));
    }


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

