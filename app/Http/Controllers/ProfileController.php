<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

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
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            // Каст 'hashed' в модели User хеширует значение автоматически
            $user->password = $validated['password'];
        }

        $user->save();

        return back()->with('success', __('messages.profile_updated'));
    }
}


