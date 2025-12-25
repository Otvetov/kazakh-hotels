<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hotel_id',
        'rating',
        'comment',
        'status',
    ];

    /**
     * Get review user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get review hotel
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Scope for approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for pending reviews
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}


