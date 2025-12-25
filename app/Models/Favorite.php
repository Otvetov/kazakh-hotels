<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hotel_id',
    ];

    /**
     * Get favorite user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get favorite hotel
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}


