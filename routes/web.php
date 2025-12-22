<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\HotelController as AdminHotelController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Hotels
Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
Route::get('/hotel/{hotel}', [HotelController::class, 'show'])->name('hotels.show');

// Bookings (authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/booking/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

// Favorites (authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorite/{hotel}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});

// Reviews (authenticated)
Route::middleware('auth')->group(function () {
    Route::post('/review', [ReviewController::class, 'store'])->name('reviews.store');
});

// Profile (authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin Panel
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    
    Route::resource('hotels', AdminHotelController::class);
    Route::resource('rooms', AdminRoomController::class);
    Route::resource('users', AdminUserController::class)->only(['index']);
    Route::post('/users/{user}/ban', [AdminUserController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user}/unban', [AdminUserController::class, 'unban'])->name('users.unban');
    
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status');
    
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
});

// Authentication routes will be added by Breeze
require __DIR__.'/auth.php';
