<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display profile page
     */
    public function show()
    {
        $user = Auth::user();
        $bookings = $user->bookings()->with(['room.hotel'])->latest()->take(5)->get();

        return view('profile.show', compact('user', 'bookings'));
    }

    /**
     * Update profile
     */
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


