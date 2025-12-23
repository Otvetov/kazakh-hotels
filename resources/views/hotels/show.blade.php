@extends('layouts.app')

@section('title', $hotel->name . ' - Kazakh Hotels')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Hotel Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                <div class="flex-1">
                    <h1 class="text-3xl md:text-4xl font-bold mb-2 text-gray-900">{{ $hotel->name }}</h1>
                    <div class="flex flex-wrap items-center gap-3 text-gray-600">
                        <div class="flex items-center gap-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ $hotel->address }}, {{ $hotel->city }}</span>
                        </div>
                        @if($hotel->rating)
                            <div class="flex items-center gap-1 bg-white px-3 py-1 rounded-full shadow-sm">
                                <span class="text-yellow-400">⭐</span>
                                <span class="font-semibold text-gray-900">{{ number_format($hotel->rating, 1) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                @auth
                    <button onclick="toggleFavorite({{ $hotel->id }})" 
                            class="p-3 bg-white rounded-full shadow-md hover:shadow-lg transition flex-shrink-0">
                        <svg class="w-6 h-6 {{ $isFavorited ? 'text-red-500 fill-current' : 'text-gray-400' }}" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>
                @endauth
            </div>

            <!-- Hotel Image -->
            @if($hotel->image)
                <div class="rounded-2xl overflow-hidden mb-6 shadow-lg">
                    <img src="{{ asset('storage/' . $hotel->image) }}" alt="{{ $hotel->name }}" class="w-full h-64 md:h-96 object-cover">
                </div>
            @endif

            <!-- Description -->
            @if($hotel->description)
                <div class="mb-8 bg-white rounded-2xl shadow-sm p-6">
                    <h2 class="text-2xl font-bold mb-4 text-gray-900">О гостинице</h2>
                    <p class="text-gray-700 leading-relaxed">{{ $hotel->description }}</p>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Rooms Table -->
            <div class="lg:col-span-2">
                <h2 class="text-2xl font-bold mb-6 text-gray-900">Доступные номера</h2>
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="divide-y divide-gray-100">
                        @forelse($hotel->rooms as $room)
                            <div class="p-6 hover:bg-gray-50 transition">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $room->name }}</h3>
                                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                </svg>
                                                <span>{{ $room->capacity }} {{ $room->capacity == 1 ? 'гость' : 'гостей' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-gray-900">{{ number_format($room->price_per_night, 0) }} ₸</div>
                                            <div class="text-sm text-gray-500">за ночь</div>
                                        </div>
                                        @auth
                                            <a href="{{ route('bookings.create', ['room_id' => $room->id]) }}" 
                                               class="px-6 py-3 bg-[#0066cc] text-white rounded-xl hover:bg-[#0052a3] transition font-semibold whitespace-nowrap">
                                                Забронировать
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}" 
                                               class="px-6 py-3 bg-[#0066cc] text-white rounded-xl hover:bg-[#0052a3] transition font-semibold whitespace-nowrap">
                                                Войти для бронирования
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center">
                                <p class="text-gray-500">Номера пока не добавлены</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div>
                <h2 class="text-2xl font-bold mb-6 text-gray-900">Отзывы</h2>
                
                @auth
                    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                        <h3 class="font-semibold mb-4 text-gray-900">Оставить отзыв</h3>
                        <form action="{{ route('reviews.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2 text-gray-700">Оценка</label>
                                <select name="rating" required class="w-full px-4 py-2 border border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-[#0066cc] focus:border-[#0066cc] outline-none">
                                    <option value="">Выберите оценку</option>
                                    <option value="5">5 звёзд</option>
                                    <option value="4">4 звезды</option>
                                    <option value="3">3 звезды</option>
                                    <option value="2">2 звезды</option>
                                    <option value="1">1 звезда</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2 text-gray-700">Комментарий</label>
                                <textarea name="comment" required rows="4" 
                                          class="w-full px-4 py-2 border border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-[#0066cc] focus:border-[#0066cc] outline-none"
                                          placeholder="Поделитесь своим опытом..."></textarea>
                            </div>

                            <button type="submit" class="w-full px-4 py-3 bg-[#0066cc] text-white rounded-xl hover:bg-[#0052a3] transition font-semibold">
                                Отправить отзыв
                            </button>
                        </form>
                    </div>
                @endauth

                <div class="space-y-4">
                    @forelse($hotel->reviews as $review)
                        <div class="bg-white rounded-2xl shadow-sm p-5">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-[#0066cc] flex items-center justify-center text-white font-semibold flex-shrink-0">
                                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $review->user->name }}</p>
                                        <div class="flex items-center gap-1 mt-1">
                                            @for($i = 0; $i < 5; $i++)
                                                <span class="text-yellow-400 text-sm {{ $i < $review->rating ? '' : 'opacity-30' }}">⭐</span>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500 flex-shrink-0">{{ $review->created_at->format('d.m.Y') }}</span>
                            </div>
                            <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                            <p class="text-gray-500">Пока нет отзывов. Будьте первым!</p>
                        </div>
                    @endforelse
                </div>
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

