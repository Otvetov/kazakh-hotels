<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display rooms list
     */
    public function index(Request $request)
    {
        $query = Room::with(['hotel', 'bookings' => function ($q) {
            $q->where('status', '!=', 'cancelled')
              ->where('check_out', '>=', now())
              ->with('user')
              ->orderBy('check_in', 'asc');
        }]);

        // Filter by booked status
        if ($request->filled('booked')) {
            if ($request->booked == '1') {
                // Show only rooms with active bookings
                $query->whereHas('bookings', function ($q) {
                    $q->where('status', '!=', 'cancelled')
                      ->where('check_out', '>=', now());
                });
            } else {
                // Show only rooms without active bookings
                $query->whereDoesntHave('bookings', function ($q) {
                    $q->where('status', '!=', 'cancelled')
                      ->where('check_out', '>=', now());
                });
            }
        }

        $rooms = $query->latest()->paginate(15);

        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Show room form
     */
    public function create()
    {
        $hotels = Hotel::orderBy('name')->get();

        return view('admin.rooms.create', compact('hotels'));
    }

    /**
     * Store room
     */
    public function store(StoreRoomRequest $request)
    {
        Room::create($request->validated());

        return redirect()->route('admin.rooms.index')->with('success', 'Номер успешно создан.');
    }

    /**
     * Show room edit form
     */
    public function edit(Room $room)
    {
        $hotels = Hotel::orderBy('name')->get();

        return view('admin.rooms.edit', compact('room', 'hotels'));
    }

    /**
     * Update room
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        $room->update($request->validated());

        return redirect()->route('admin.rooms.index')->with('success', 'Номер успешно обновлен.');
    }

    /**
     * Delete room
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Номер успешно удален.');
    }
}


