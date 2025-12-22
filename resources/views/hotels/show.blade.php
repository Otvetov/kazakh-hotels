@extends('layouts.app')

@section('title', $hotel->name . ' - Kazakh Hotels')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Hotel Header -->
    <div class="mb-8">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-4xl font-bold mb-2">{{ $hotel->name }}</h1>
                <p class="text-gray-600 dark:text-gray-400">{{ $hotel->address }}, {{ $hotel->city }}</p>
                @if($hotel->rating)
                    <div class="flex items-center mt-2">
                        <span class="text-yellow-400 text-xl">★</span>
                        <span class="ml-2 text-lg font-semibold">{{ number_format($hotel->rating, 1) }}</span>
                    </div>
                @endif
            </div>
            @auth
                <button onclick="toggleFavorite({{ $hotel->id }})" 
                        class="p-3 bg-white dark:bg-gray-800 rounded-full shadow-md hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <svg class="w-6 h-6 {{ $isFavorited ? 'text-red-500 fill-current' : 'text-gray-400' }}" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </button>
            @endauth
        </div>

        <!-- Hotel Image -->
        @if($hotel->image)
            <div class="rounded-lg overflow-hidden mb-6">
                <img src="{{ asset('storage/' . $hotel->image) }}" alt="{{ $hotel->name }}" class="w-full h-96 object-cover">
            </div>
        @endif

        <!-- Description -->
        @if($hotel->description)
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-4">About</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $hotel->description }}</p>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Rooms Table -->
        <div class="lg:col-span-2">
            <h2 class="text-2xl font-bold mb-4">Available Rooms</h2>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Room</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Capacity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Price/Night</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($hotel->rooms as $room)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4">{{ $room->name }}</td>
                                <td class="px-6 py-4">{{ $room->capacity }} guests</td>
                                <td class="px-6 py-4 font-semibold text-[#38b000]">{{ number_format($room->price_per_night, 0) }} ₸</td>
                                <td class="px-6 py-4">
                                    @auth
                                        <a href="{{ route('bookings.create', ['room_id' => $room->id]) }}" 
                                           class="px-4 py-2 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8a00] transition text-sm">
                                            Book Now
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" 
                                           class="px-4 py-2 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8a00] transition text-sm">
                                            Login to Book
                                        </a>
                                    @endauth
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No rooms available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Reviews Section -->
        <div>
            <h2 class="text-2xl font-bold mb-4">Reviews</h2>
            
            @auth
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="font-semibold mb-4">Write a Review</h3>
                    <form action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Rating</label>
                            <select name="rating" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                                <option value="">Select rating</option>
                                <option value="5">5 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="2">2 Stars</option>
                                <option value="1">1 Star</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Comment</label>
                            <textarea name="comment" required rows="4" 
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700"
                                      placeholder="Share your experience..."></textarea>
                        </div>

                        <button type="submit" class="w-full px-4 py-2 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8a00] transition">
                            Submit Review
                        </button>
                    </form>
                </div>
            @endauth

            <div class="space-y-4">
                @forelse($hotel->reviews as $review)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-[#38b000] flex items-center justify-center text-white font-semibold mr-3">
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold">{{ $review->user->name }}</p>
                                    <div class="flex items-center">
                                        @for($i = 0; $i < 5; $i++)
                                            <span class="text-yellow-400 {{ $i < $review->rating ? '' : 'opacity-30' }}">★</span>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $review->created_at->format('M Y') }}</span>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300">{{ $review->comment }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">No reviews yet. Be the first to review!</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@auth
<script>
function toggleFavorite(hotelId) {
    fetch(`/favorite/${hotelId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        location.reload();
    });
}
</script>
@endauth
@endsection

