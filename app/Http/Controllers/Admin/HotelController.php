<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display hotels list
     */
    public function index()
    {
        $hotels = Hotel::with('rooms')->latest()->paginate(15);

        return view('admin.hotels.index', compact('hotels'));
    }

    /**
     * Show hotel form
     */
    public function create()
    {
        return view('admin.hotels.create');
    }

    /**
     * Store hotel
     */
    public function store(StoreHotelRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hotels', 'public');
        }

        Hotel::create($data);

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel created successfully.');
    }

    /**
     * Show hotel edit form
     */
    public function edit(Hotel $hotel)
    {
        return view('admin.hotels.edit', compact('hotel'));
    }

    /**
     * Update hotel
     */
    public function update(UpdateHotelRequest $request, Hotel $hotel)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($hotel->image) {
                Storage::disk('public')->delete($hotel->image);
            }
            $data['image'] = $request->file('image')->store('hotels', 'public');
        }

        $hotel->update($data);

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel updated successfully.');
    }

    /**
     * Delete hotel
     */
    public function destroy(Hotel $hotel)
    {
        if ($hotel->image) {
            Storage::disk('public')->delete($hotel->image);
        }

        $hotel->delete();

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel deleted successfully.');
    }
}

