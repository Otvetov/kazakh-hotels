@extends('layouts.app')

@section('title', 'Book Room - Kazakh Hotels')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Book Your Room</h1>

    @if($room)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8 border border-gray-200 dark:border-gray-700">
            <h2 class="text-2xl font-semibold mb-2">{{ $room->hotel->name }}</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $room->name }} - Capacity: {{ $room->capacity }} guests</p>
            <p class="text-xl font-bold text-[#38b000]">{{ number_format($room->price_per_night, 0) }} ₸ per night</p>
        </div>
    @endif

    <form action="{{ route('bookings.store') }}" method="POST" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
        @csrf

        @if($room)
            <input type="hidden" name="room_id" value="{{ $room->id }}">
        @else
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Select Room</label>
                <select name="room_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                    <option value="">Choose a room...</option>
                    <!-- Rooms will be loaded here -->
                </select>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium mb-2">Check-in Date</label>
                <input type="date" name="check_in" required 
                       min="{{ date('Y-m-d') }}"
                       value="{{ old('check_in') }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Check-out Date</label>
                <input type="date" name="check_out" required 
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                       value="{{ old('check_out') }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">Number of Guests</label>
            <input type="number" name="guests" required min="1" 
                   value="{{ old('guests', $room->capacity ?? 1) }}"
                   max="{{ $room->capacity ?? 10 }}"
                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
        </div>

        <div id="price-calculation" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <p class="text-lg font-semibold">Total Price: <span id="total-price">0</span> ₸</p>
            <p class="text-sm text-gray-600 dark:text-gray-400" id="nights-info">Select dates to calculate price</p>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="flex-1 px-6 py-3 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8a00] transition font-semibold">
                Confirm Booking
            </button>
            <a href="{{ url()->previous() }}" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkIn = document.querySelector('input[name="check_in"]');
    const checkOut = document.querySelector('input[name="check_out"]');
    const pricePerNight = {{ $room->price_per_night ?? 0 }};
    
    function calculatePrice() {
        if (checkIn.value && checkOut.value) {
            const checkInDate = new Date(checkIn.value);
            const checkOutDate = new Date(checkOut.value);
            const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
            
            if (nights > 0) {
                const total = nights * pricePerNight;
                document.getElementById('total-price').textContent = total.toLocaleString();
                document.getElementById('nights-info').textContent = `${nights} night${nights > 1 ? 's' : ''} × ${pricePerNight.toLocaleString()} ₸`;
            }
        }
    }
    
    checkIn.addEventListener('change', function() {
        if (checkOut.value) {
            checkOut.min = new Date(new Date(checkIn.value).getTime() + 86400000).toISOString().split('T')[0];
        }
        calculatePrice();
    });
    
    checkOut.addEventListener('change', calculatePrice);
});
</script>
@endsection

