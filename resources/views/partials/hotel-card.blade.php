@php $bkQuery = http_build_query(array_filter(request()->only(['check_in', 'check_out', 'guests', 'rooms']))); @endphp
<a href="{{ route('hotels.show', $hotel->id) }}{{ $bkQuery ? '?'.$bkQuery : '' }}"
   class="group block otl-surface overflow-hidden hover:ring-2 hover:ring-[#8ee30f]/40 transition">

    <div class="relative aspect-[4/3] overflow-hidden">
        @if($hotel->image)
            <img src="{{ $hotel->image_url }}"
                 alt="{{ $hotel->name }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center bg-[#141516]">
                <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </div>
        @endif
        @auth
            <button onclick="event.stopPropagation(); event.preventDefault(); toggleFavorite({{ $hotel->id }})"
                    class="absolute top-3 right-3 p-2 bg-black/40 backdrop-blur-sm rounded-full hover:bg-black/60 transition">
                <svg class="w-5 h-5 {{ $hotel->isFavoritedBy(auth()->id()) ? 'text-[#f04141] fill-current' : 'text-white' }}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </button>
        @endauth
    </div>

    <div class="p-4">
        @if($hotel->rating)
            <div class="flex items-center gap-1.5 text-sm font-semibold text-[#8ee30f] mb-1">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.37 4.24a1 1 0 00.95.69h4.46c.97 0 1.37 1.24.59 1.81l-3.61 2.62a1 1 0 00-.36 1.12l1.38 4.24c.3.92-.75 1.69-1.54 1.12l-3.6-2.62a1 1 0 00-1.18 0l-3.6 2.62c-.79.57-1.84-.2-1.54-1.12l1.38-4.24a1 1 0 00-.36-1.12L1.33 9.67c-.78-.57-.38-1.81.59-1.81h4.46a1 1 0 00.95-.69z"/></svg>
                {{ number_format($hotel->rating, 1) }}
            </div>
        @endif
        <h3 class="font-semibold text-white leading-snug line-clamp-1">{{ $hotel->name }}</h3>
        <p class="text-sm text-[#7e8488] mb-3">{{ $hotel->city }}</p>
        <div class="flex items-end justify-between">
            <div class="text-lg font-bold text-[#8ee30f]">
                {{ __('messages.from_price', ['price' => number_format($hotel->min_price, 0, '.', ' ')]) }}
            </div>
            <div class="text-xs text-[#7e8488]">{{ trans_choice('messages.rooms_count', $hotel->rooms->count(), ['count' => $hotel->rooms->count()]) }}</div>
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
