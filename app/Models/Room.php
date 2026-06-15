<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
        'image',
        'price_per_night',
        'capacity',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'price_per_night' => 'decimal:2',
            'is_available' => 'boolean',
        ];
    }


    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * URL изображения номера (поддерживает внешние ссылки и пути в storage).
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }


    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }


    public function isAvailableForDates($checkIn, $checkOut): bool
    {
        if (!$this->is_available) {
            return false;
        }

        $conflictingBookings = $this->bookings()
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in', '<=', $checkIn)
                          ->where('check_out', '>=', $checkOut);
                    });
            })
            ->exists();

        return !$conflictingBookings;
    }
}


