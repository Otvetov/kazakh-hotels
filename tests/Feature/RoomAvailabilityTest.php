<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_free_room_is_available(): void
    {
        $room = Room::factory()->create(['is_available' => true]);

        $response = $this->getJson(route('rooms.availability', $room) . '?' . http_build_query([
            'check_in' => now()->addDays(10)->toDateString(),
            'check_out' => now()->addDays(12)->toDateString(),
        ]));

        $response->assertOk()->assertJson(['available' => true]);
    }

    public function test_booked_room_reports_busy_until_date(): void
    {
        $room = Room::factory()->create(['is_available' => true]);

        Booking::factory()->create([
            'room_id' => $room->id,
            'check_in' => now()->addDays(10)->toDateString(),
            'check_out' => now()->addDays(15)->toDateString(),
            'status' => 'confirmed',
        ]);

        $response = $this->getJson(route('rooms.availability', $room) . '?' . http_build_query([
            'check_in' => now()->addDays(12)->toDateString(),
            'check_out' => now()->addDays(14)->toDateString(),
        ]));

        $response->assertOk()
            ->assertJson(['available' => false])
            ->assertJsonStructure(['available', 'busy_until']);
    }
}
