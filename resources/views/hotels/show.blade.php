@extends('layouts.app')

@section('title', $hotel->name . ' - Kazakh Hotels')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <button
            onclick="window.history.back()"
            class="flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span>Назад</span>
        </button>

        <!-- Hotel Header -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-8">
            <div class="relative h-96">
                @if($hotel->image)
                    <img
                        src="{{ asset('storage/' . $hotel->image) }}"
                        alt="{{ $hotel->name }}"
                        class="w-full h-full object-cover"
                    />
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                        <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                @endif
                @if($hotel->rating)
                    <div class="absolute top-6 right-6 bg-white px-4 py-2 rounded-full flex items-center gap-2">
                        <svg class="w-5 h-5 fill-yellow-400 text-yellow-400" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-1.07 3.927a1 1 0 01-.39 1.18l-3.462 2.462c-.969.69-2.157-.38-1.902-1.81l1.07-3.292a1 1 0 00-.95-.69H5.577c-.969 0-1.371-1.24-.588-1.81l3.462-2.462a1 1 0 01.39-1.18z"/>
                        </svg>
                        <span class="text-gray-900 font-semibold">{{ number_format($hotel->rating, 1) }}</span>
                    </div>
                @endif
            </div>

            <div class="p-8">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h1 class="text-gray-900 mb-2 text-2xl font-bold">{{ $hotel->name }}</h1>
                        @if($hotel->stars)
                            <div class="flex items-center gap-1 mb-3">
                                @for($i = 0; $i < $hotel->stars; $i++)
                                    <svg class="w-5 h-5 fill-yellow-400 text-yellow-400" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-1.07 3.927a1 1 0 01-.39 1.18l-3.462 2.462c-.969.69-2.157-.38-1.902-1.81l1.07-3.292a1 1 0 00-.95-.69H5.577c-.969 0-1.371-1.24-.588-1.81l3.462-2.462a1 1 0 01.39-1.18z"/>
                                    </svg>
                                @endfor
                            </div>
                        @endif
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ $hotel->city }}, {{ $hotel->address }}</span>
                        </div>
                    </div>
                </div>

                @if($hotel->description)
                    <p class="text-gray-600 leading-relaxed">
                        {{ $hotel->description }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Available Rooms -->
        <div class="mb-8">
            <h2 class="text-gray-900 mb-6 text-xl font-bold">Доступные номера</h2>

            @if($hotel->rooms->count() === 0)
                <div class="bg-white rounded-2xl shadow-sm p-8 text-center">
                    <p class="text-gray-500">Нет доступных номеров</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($hotel->rooms as $room)
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow">
                            @if($room->image)
                                <div class="h-48 overflow-hidden">
                                    <img
                                        src="{{ asset('storage/' . $room->image) }}"
                                        alt="{{ $room->name }}"
                                        class="w-full h-full object-cover"
                                    />
                                </div>
                            @endif
                            
                            <div class="p-6">
                                <h3 class="text-gray-900 mb-2 font-semibold">{{ $room->name }}</h3>
                                
                                <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <span>До {{ $room->capacity }} {{ $room->capacity == 1 ? 'гостя' : 'гостей' }}</span>
                                </div>

                                @if($room->description)
                                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                        {{ $room->description }}
                                    </p>
                                @endif

                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <div class="text-gray-500 text-sm">За ночь</div>
                                        <div class="text-gray-900 font-semibold">
                                            {{ number_format($room->price_per_night, 0) }} ₸
                                        </div>
                                    </div>
                                </div>

                                @auth
                                    <a href="{{ route('bookings.create', ['room_id' => $room->id]) }}" 
                                       class="block w-full py-2 text-center bg-[#38b000] text-white rounded-lg hover:bg-[#2d8c00] transition-colors">
                                        Забронировать
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="block w-full py-2 text-center bg-[#38b000] text-white rounded-lg hover:bg-[#2d8c00] transition-colors">
                                        Войти для бронирования
                                    </a>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Reviews Section -->
        @include('partials.review-section', ['hotel' => $hotel])
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
        // Обновляем иконку без перезагрузки страницы
        const button = event.target.closest('button');
        const svg = button.querySelector('svg');
        if (data.is_favorited) {
            svg.classList.remove('text-gray-400');
            svg.classList.add('text-red-500', 'fill-current');
        } else {
            svg.classList.remove('text-red-500', 'fill-current');
            svg.classList.add('text-gray-400');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        location.reload();
    });
}
</script>
@endauth
@endsection

