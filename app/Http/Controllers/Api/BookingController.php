<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Get user bookings
     */
    public function index(Request $request): JsonResponse
    {
        $query = Auth::user()->bookings()->with(['room.hotel']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tab')) {
            switch ($request->tab) {
                case 'past':
                    $query->where('check_out', '<', now());
                    break;
                case 'cancelled':
                    $query->where('status', 'cancelled');
                    break;
                default:
                    $query->where('check_out', '>=', now())->where('status', '!=', 'cancelled');
            }
        }

        $bookings = $query->latest()->paginate($request->get('per_page', 10));

        return response()->json([
            'data' => $bookings->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'hotel_name' => $booking->room->hotel->name,
                    'room_name' => $booking->room->name,
                    'check_in' => $booking->check_in->format('Y-m-d'),
                    'check_out' => $booking->check_out->format('Y-m-d'),
                    'guests' => $booking->guests,
                    'total_price' => $booking->total_price,
                    'status' => $booking->status,
                    'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'meta' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'per_page' => $bookings->perPage(),
                'total' => $bookings->total(),
            ],
        ]);
    }

    /**
     * Create booking
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        $room = Room::findOrFail($request->room_id);

        if (!$room->isAvailableForDates($request->check_in, $request->check_out)) {
            return response()->json([
                'message' => 'Room is not available for selected dates.',
            ], 422);
        }

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

        $booking->load(['room.hotel']);

        return response()->json([
            'message' => 'Booking created successfully',
            'data' => [
                'id' => $booking->id,
                'hotel_name' => $booking->room->hotel->name,
                'room_name' => $booking->room->name,
                'check_in' => $booking->check_in->format('Y-m-d'),
                'check_out' => $booking->check_out->format('Y-m-d'),
                'guests' => $booking->guests,
                'total_price' => $booking->total_price,
                'status' => $booking->status,
            ],
        ], 201);
    }

    /**
     * Get booking details
     */
    public function show(Booking $booking): JsonResponse
    {
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $booking->load(['room.hotel', 'user']);

        return response()->json([
            'data' => [
                'id' => $booking->id,
                'hotel' => [
                    'id' => $booking->room->hotel->id,
                    'name' => $booking->room->hotel->name,
                    'address' => $booking->room->hotel->address,
                ],
                'room' => [
                    'id' => $booking->room->id,
                    'name' => $booking->room->name,
                ],
                'check_in' => $booking->check_in->format('Y-m-d'),
                'check_out' => $booking->check_out->format('Y-m-d'),
                'guests' => $booking->guests,
                'total_price' => $booking->total_price,
                'status' => $booking->status,
                'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    /**
     * Cancel booking
     */
    public function cancel(Booking $booking): JsonResponse
    {
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($booking->status === 'cancelled') {
            return response()->json([
                'message' => 'Booking is already cancelled.',
            ], 422);
        }

        $booking->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Booking cancelled successfully',
            'data' => [
                'id' => $booking->id,
                'status' => $booking->status,
            ],
        ]);
    }
}


