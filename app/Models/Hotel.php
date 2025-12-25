<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'address',
        'description',
        'rating',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'decimal:1',
        ];
    }

    /**
     * Get hotel rooms
     */
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Get hotel bookings
     */
    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Room::class);
    }

    /**
     * Get hotel favorites
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get approved reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    /**
     * Get all reviews (including pending)
     */
    public function allReviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Check if hotel is favorited by user
     */
    public function isFavoritedBy($userId): bool
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }

    /**
     * Get minimum room price
     */
    public function getMinPriceAttribute()
    {
        return $this->rooms()->min('price_per_night') ?? 0;
    }

    /**
     * Get image URL (supports both external URLs and local storage paths)
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        // Check if it's a full URL (starts with http:// or https://)
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        // Otherwise, treat it as a local storage path
        return asset('storage/' . $this->image);
    }
}


