<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'banned_at',
    ];

    /**
     * Атрибуты, которые скрываются при сериализации.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Атрибуты, которые должны быть приведены к нужным типам.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'banned_at' => 'datetime',
        ];
    }


    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }


    public function isBanned(): bool
    {
        return $this->banned_at !== null;
    }


    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }


    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }


    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
