@foreach($hotels as $hotel)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-xl transition overflow-hidden cursor-pointer"
         onclick="window.location='{{ route('hotels.show', $hotel) }}'">
        <div class="relative h-48 bg-gray-200 dark:bg-gray-700">
            @if($hotel->image)
                <img src="{{ asset('storage/' . $hotel->image) }}" alt="{{ $hotel->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-gray-400">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
            @endif
            @auth
                <button onclick="event.stopPropagation(); toggleFavorite({{ $hotel->id }})" 
                        class="absolute top-2 right-2 p-2 bg-white dark:bg-gray-800 rounded-full shadow-md hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <svg class="w-5 h-5 {{ $hotel->isFavoritedBy(auth()->id()) ? 'text-red-500 fill-current' : 'text-gray-400' }}" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </button>
            @endauth
        </div>
        <div class="p-4">
            <h3 class="text-xl font-semibold mb-1">{{ $hotel->name }}</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">{{ $hotel->city }}</p>
            @if($hotel->rating)
                <div class="flex items-center mb-2">
                    <span class="text-yellow-400">★</span>
                    <span class="ml-1">{{ number_format($hotel->rating, 1) }}</span>
                </div>
            @endif
            <div class="flex justify-between items-center">
                <span class="text-lg font-bold text-[#38b000]">
                    {{ number_format($hotel->min_price, 0) }} ₸/night
                </span>
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $hotel->rooms->count() }} rooms
                </span>
            </div>
        </div>
    </div>
@endforeach

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

