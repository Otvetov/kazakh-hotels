@extends('layouts.app')

@section('title', 'Booking Details - Kazakh Hotels')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Booking Confirmation</h1>

    <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-6 py-4 rounded-lg mb-6">
        <p class="font-semibold">✓ Your booking has been created successfully!</p>
        <p class="text-sm mt-1">Booking ID: #{{ $booking->id }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
        <h2 class="text-2xl font-semibold mb-4">Booking Details</h2>
        
        <div class="space-y-4">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Hotel</p>
                <p class="text-lg font-semibold">{{ $booking->room->hotel->name }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Room</p>
                <p class="text-lg font-semibold">{{ $booking->room->name }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Check-in</p>
                    <p class="text-lg font-semibold">{{ $booking->check_in->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Check-out</p>
                    <p class="text-lg font-semibold">{{ $booking->check_out->format('M d, Y') }}</p>
                </div>
            </div>

            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Guests</p>
                <p class="text-lg font-semibold">{{ $booking->guests }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Price</p>
                <p class="text-2xl font-bold text-[#38b000]">{{ number_format($booking->total_price, 0) }} ₸</p>
            </div>

            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Status</p>
                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold 
                    {{ $booking->status === 'confirmed' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 
                       ($booking->status === 'cancelled' ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' : 
                        'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200') }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>
        </div>

        <div class="mt-6 flex gap-4">
            <a href="{{ route('bookings.index') }}" class="px-6 py-3 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8a00] transition">
                View All Bookings
            </a>
            <a href="{{ route('hotels.show', $booking->room->hotel) }}" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                Back to Hotel
            </a>
        </div>
    </div>
</div>
@endsection

