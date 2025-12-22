<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'check_in',
        'check_out',
        'guests',
        'total_price',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'check_in' => 'date',
            'check_out' => 'date',
            'total_price' => 'decimal:2',
        ];
    }

    /**
     * Get booking user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get booking room
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get booking hotel through room
     */
    public function getHotelAttribute()
    {
        return $this->room->hotel;
    }

    /**
     * Calculate number of nights
     */
    public function getNightsAttribute()
    {
        return $this->check_in->diffInDays($this->check_out);
    }
}

