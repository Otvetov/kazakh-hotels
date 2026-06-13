<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use App\Models\Hotel;
use App\Models\HotelImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }


    public function index()
    {
        $hotels = Hotel::with('rooms')->latest()->paginate(15);

        return view('admin.hotels.index', compact('hotels'));
    }

    public function create()
    {
        return view('admin.hotels.create');
    }


    public function store(StoreHotelRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hotels', 'public');
        }

        $hotel = Hotel::create($data);

        $this->storeGalleryImages($request, $hotel);

        return redirect()->route('admin.hotels.index')->with('success', 'Отель успешно создан.');
    }


    public function edit(Hotel $hotel)
    {
        return view('admin.hotels.edit', compact('hotel'));
    }


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

        $this->storeGalleryImages($request, $hotel);

        return redirect()->route('admin.hotels.index')->with('success', 'Отель успешно обновлен.');
    }


    public function destroy(Hotel $hotel)
    {
        if ($hotel->image) {
            Storage::disk('public')->delete($hotel->image);
        }

        // Удаляем файлы галереи (записи удалятся каскадом)
        foreach ($hotel->images as $img) {
            if (!str_starts_with($img->image, 'http')) {
                Storage::disk('public')->delete($img->image);
            }
        }

        $hotel->delete();

        return redirect()->route('admin.hotels.index')->with('success', 'Отель успешно удален.');
    }

    /**
     * Удалить одно изображение галереи.
     */
    public function deleteImage(Hotel $hotel, HotelImage $image)
    {
        abort_unless($image->hotel_id === $hotel->id, 404);

        if (!str_starts_with($image->image, 'http')) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        return back()->with('success', 'Фото удалено.');
    }

    /**
     * Сохранить загруженные изображения галереи.
     */
    private function storeGalleryImages(Request $request, Hotel $hotel): void
    {
        if (!$request->hasFile('gallery_images')) {
            return;
        }

        $order = (int) $hotel->images()->max('sort_order') + 1;

        foreach ($request->file('gallery_images') as $file) {
            $hotel->images()->create([
                'image' => $file->store('hotels', 'public'),
                'sort_order' => $order++,
            ]);
        }
    }
}


