@extends('layouts.app')

@section('title', 'My Bookings - Kazakh Hotels')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">My Bookings</h1>

    <!-- Tabs -->
    <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex space-x-4">
            <a href="{{ route('bookings.index', ['tab' => 'active']) }}" 
               class="px-4 py-2 {{ $tab === 'active' ? 'border-b-2 border-[#38b000] text-[#38b000] font-semibold' : 'text-gray-600 dark:text-gray-400 hover:text-[#38b000]' }}">
                Active
            </a>
            <a href="{{ route('bookings.index', ['tab' => 'past']) }}" 
               class="px-4 py-2 {{ $tab === 'past' ? 'border-b-2 border-[#38b000] text-[#38b000] font-semibold' : 'text-gray-600 dark:text-gray-400 hover:text-[#38b000]' }}">
                Past
            </a>
            <a href="{{ route('bookings.index', ['tab' => 'cancelled']) }}" 
               class="px-4 py-2 {{ $tab === 'cancelled' ? 'border-b-2 border-[#38b000] text-[#38b000] font-semibold' : 'text-gray-600 dark:text-gray-400 hover:text-[#38b000]' }}">
                Cancelled
            </a>
        </div>
    </div>

    <!-- Bookings List -->
    <div class="space-y-4">
        @forelse($bookings as $booking)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold mb-2">{{ $booking->room->hotel->name }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">{{ $booking->room->name }}</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Check-in</p>
                                <p class="font-semibold">{{ $booking->check_in->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Check-out</p>
                                <p class="font-semibold">{{ $booking->check_out->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Guests</p>
                                <p class="font-semibold">{{ $booking->guests }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Total</p>
                                <p class="font-semibold text-[#38b000]">{{ number_format($booking->total_price, 0) }} ₸</p>
                            </div>
                        </div>
                    </div>
                    <div class="ml-6 text-right">
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold mb-4
                            {{ $booking->status === 'confirmed' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 
                               ($booking->status === 'cancelled' ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' : 
                                'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200') }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                        @if($booking->status !== 'cancelled' && $booking->check_out >= now())
                            <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm">
                                    Cancel
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center border border-gray-200 dark:border-gray-700">
                <p class="text-gray-500 dark:text-gray-400 text-lg">No bookings found.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $bookings->links() }}
    </div>
</div>
@endsection

