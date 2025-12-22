<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    /**
     * Determine if user can view booking
     */
    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id || $user->isAdmin();
    }

    /**
     * Determine if user can update booking
     */
    public function update(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id || $user->isAdmin();
    }
}

