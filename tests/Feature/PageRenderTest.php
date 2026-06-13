<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads(): void
    {
        Room::factory()->create();

        $this->get('/')->assertOk();
    }

    public function test_catalog_page_loads(): void
    {
        Room::factory()->create();

        $this->get(route('hotels.index'))->assertOk();
    }

    public function test_hotel_detail_page_loads(): void
    {
        $hotel = Hotel::factory()->create();
        Room::factory()->create(['hotel_id' => $hotel->id]);

        $this->get(route('hotels.show', $hotel))->assertOk();
    }

    public function test_locale_switch_works(): void
    {
        $this->get(route('locale.switch', 'kk'))->assertRedirect();
        $this->assertEquals('kk', session('locale'));
    }
}
