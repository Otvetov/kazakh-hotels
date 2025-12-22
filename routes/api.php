<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Support\Facades\Route;

// Public API routes
Route::prefix('v1')->group(function () {
    // Authentication
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Public hotel data
    Route::get('/hotels', [HotelController::class, 'index']);
    Route::get('/hotels/{hotel}', [HotelController::class, 'show']);
    Route::get('/hotels/{hotel}/rooms', [HotelController::class, 'rooms']);
});

// Protected API routes (require authentication)
Route::prefix('v1')->middleware('auth')->group(function () {
    // User profile
    Route::get('/user', [ProfileController::class, 'show']);
    Route::put('/user', [ProfileController::class, 'update']);

    // Bookings
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/{booking}', [BookingController::class, 'show']);
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites/{hotel}', [FavoriteController::class, 'toggle']);
    Route::delete('/favorites/{hotel}', [FavoriteController::class, 'remove']);

    // Reviews
    Route::post('/hotels/{hotel}/reviews', [ReviewController::class, 'store']);
    Route::get('/hotels/{hotel}/reviews', [ReviewController::class, 'index']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});

