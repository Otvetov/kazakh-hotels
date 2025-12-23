<a href="{{ route('hotels.show', $hotel->id) }}"
   class="group bg-white rounded-2xl overflow-hidden shadow hover:shadow-xl transition cursor-pointer">

    <div class="relative h-48 overflow-hidden">
        @if($hotel->image)
            <img src="{{ asset('storage/' . $hotel->image) }}"
                 alt="{{ $hotel->name }}"
                 class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </div>
        @endif
        @if($hotel->rating)
            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-medium">
                ⭐ {{ number_format($hotel->rating, 1) }}
            </div>
        @endif
        @auth
            <button onclick="event.stopPropagation(); event.preventDefault(); toggleFavorite({{ $hotel->id }})" 
                    class="absolute top-4 right-4 p-2 bg-white/90 backdrop-blur-sm rounded-full shadow-md hover:bg-white transition">
                <svg class="w-5 h-5 {{ $hotel->isFavoritedBy(auth()->id()) ? 'text-red-500 fill-current' : 'text-gray-400' }}" 
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </button>
        @endauth
    </div>

    <div class="p-5">
        <h3 class="font-semibold mb-1 text-gray-900">{{ $hotel->name }}</h3>
        <p class="text-sm text-gray-500 mb-2">{{ $hotel->city }}</p>
        @if($hotel->description)
            <p class="text-sm text-gray-600 line-clamp-2 mb-3">
                {{ $hotel->description }}
            </p>
        @endif
        <div class="flex justify-between items-center pt-3 border-t border-gray-100">
            <div>
                <div class="text-lg font-bold text-gray-900">
                    {{ number_format($hotel->min_price, 0) }} ₸
                </div>
                <div class="text-xs text-gray-500">за ночь</div>
            </div>
            <div class="text-sm text-gray-600">
                {{ $hotel->rooms->count() }} номеров
            </div>
        </div>
    </div>
</a>

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


