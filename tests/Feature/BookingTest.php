<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private function room(int $capacity = 2): Room
    {
        return Room::factory()->create(['capacity' => $capacity, 'is_available' => true]);
    }

    public function test_authenticated_user_can_create_booking(): void
    {
        $user = User::factory()->create();
        $room = $this->room(2);

        $response = $this->actingAs($user)->post(route('bookings.store'), [
            'room_id' => $room->id,
            'check_in' => now()->addDays(10)->toDateString(),
            'check_out' => now()->addDays(12)->toDateString(),
            'guests' => 2,
        ]);

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'room_id' => $room->id,
            'guests' => 2,
        ]);
    }

    public function test_guests_cannot_exceed_room_capacity(): void
    {
        $user = User::factory()->create();
        $room = $this->room(2);

        $response = $this->actingAs($user)->post(route('bookings.store'), [
            'room_id' => $room->id,
            'check_in' => now()->addDays(10)->toDateString(),
            'check_out' => now()->addDays(12)->toDateString(),
            'guests' => 3,
        ]);

        $response->assertSessionHasErrors('guests');
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_room_cannot_be_double_booked_for_overlapping_dates(): void
    {
        $user = User::factory()->create();
        $room = $this->room(2);

        Booking::factory()->create([
            'room_id' => $room->id,
            'check_in' => now()->addDays(10)->toDateString(),
            'check_out' => now()->addDays(15)->toDateString(),
            'status' => 'confirmed',
        ]);

        $this->actingAs($user)->post(route('bookings.store'), [
            'room_id' => $room->id,
            'check_in' => now()->addDays(12)->toDateString(),
            'check_out' => now()->addDays(14)->toDateString(),
            'guests' => 1,
        ]);

        // Новая бронь не создаётся — остаётся только исходная
        $this->assertDatabaseCount('bookings', 1);
    }

    public function test_guest_cannot_create_booking(): void
    {
        $room = $this->room();

        $response = $this->post(route('bookings.store'), [
            'room_id' => $room->id,
            'check_in' => now()->addDays(10)->toDateString(),
            'check_out' => now()->addDays(12)->toDateString(),
            'guests' => 1,
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('bookings', 0);
    }
}
