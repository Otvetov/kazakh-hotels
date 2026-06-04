<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    public function show()
    {
        $user = Auth::user();
        $bookings = $user->bookings()->with(['room.hotel'])->latest()->take(5)->get();

        $stats = [
            'bookings' => $user->bookings()->count(),
            'favorites' => $user->favorites()->count(),
            'reviews' => $user->reviews()->count(),
        ];

        $favorites = $user->favorites()->with('hotel.rooms')->latest()->take(4)->get();

        return view('profile.show', compact('user', 'bookings', 'stats', 'favorites'));
    }


    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        Auth::user()->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }
}


