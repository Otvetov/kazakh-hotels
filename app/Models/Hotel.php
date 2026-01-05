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
     * Получить номера отеля
     */
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Получить бронирования отеля
     */
    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Room::class);
    }

    /**
     * Получить избранные отеля
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Получить отзывы
     */
    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    /**
     * Получить все отзывы
     */
    public function allReviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Проверить, добавил ли пользователь отель в избранное
     */
    public function isFavoritedBy($userId): bool
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }

    /**
     * Получить минимальную цену номера
     */
    public function getMinPriceAttribute()
    {
        return $this->rooms()->min('price_per_night') ?? 0;
    }

    /**
     * Получить URL изображения
     * (поддерживает как внешние ссылки, так и локальные пути в storage)
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        // Проверяем, является ли изображение внешней ссылкой
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        // В противном случае считаем, что это локальный путь в storage
        return asset('storage/' . $this->image);
    }
}


