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
    public function index()
    {
        $rooms = Room::with('hotel')->latest()->paginate(15);

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

        return redirect()->route('admin.rooms.index')->with('success', 'Room created successfully.');
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

        return redirect()->route('admin.rooms.index')->with('success', 'Room updated successfully.');
    }

    /**
     * Delete room
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Room deleted successfully.');
    }
}

