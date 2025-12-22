<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Get user profile
     */
    public function show(): JsonResponse
    {
        $user = Auth::user();
        $bookings = $user->bookings()->with(['room.hotel'])->latest()->take(5)->get();

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                'recent_bookings' => $bookings->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'hotel_name' => $booking->room->hotel->name,
                        'check_in' => $booking->check_in->format('Y-m-d'),
                        'check_out' => $booking->check_out->format('Y-m-d'),
                        'total_price' => $booking->total_price,
                        'status' => $booking->status,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Update user profile
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        Auth::user()->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => Auth::user()->id,
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ]);
    }
}

