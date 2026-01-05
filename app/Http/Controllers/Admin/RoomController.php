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


    public function index(Request $request)
    {
        $query = Room::with(['hotel', 'bookings' => function ($q) {
            $q->where('status', '!=', 'cancelled')
              ->where('check_out', '>=', now())
              ->with('user')
              ->orderBy('check_in', 'asc');
        }]);

        // Фильтровать по статусу бронирования
        if ($request->filled('booked')) {
            if ($request->booked == '1') {
                // Показывать только номера с активными бронированиями
                $query->whereHas('bookings', function ($q) {
                    $q->where('status', '!=', 'cancelled')
                      ->where('check_out', '>=', now());
                });
            } else {
                // Показывать только номера без активных бронирований
                $query->whereDoesntHave('bookings', function ($q) {
                    $q->where('status', '!=', 'cancelled')
                      ->where('check_out', '>=', now());
                });
            }
        }

        $rooms = $query->latest()->paginate(15);

        return view('admin.rooms.index', compact('rooms'));
    }


    public function create()
    {
        $hotels = Hotel::orderBy('name')->get();

        return view('admin.rooms.create', compact('hotels'));
    }


    public function store(StoreRoomRequest $request)
    {
        Room::create($request->validated());

        return redirect()->route('admin.rooms.index')->with('success', 'Номер успешно создан.');
    }

    public function edit(Room $room)
    {
        $hotels = Hotel::orderBy('name')->get();

        return view('admin.rooms.edit', compact('room', 'hotels'));
    }


    public function update(UpdateRoomRequest $request, Room $room)
    {
        $room->update($request->validated());

        return redirect()->route('admin.rooms.index')->with('success', 'Номер успешно обновлен.');
    }


    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Номер успешно удален.');
    }
}


