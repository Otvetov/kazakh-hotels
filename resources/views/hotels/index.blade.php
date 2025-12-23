@extends('layouts.app')

@section('title', 'Каталог отелей - Kazakh Hotels')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-gray-900 mb-2 text-2xl font-bold">
                    {{ request('city') ? 'Отели в ' . request('city') : 'Все отели' }}
                </h1>
                <p class="text-gray-600">
                    Найдено {{ $hotels->total() }} {{ $hotels->total() == 1 ? 'отель' : 'отелей' }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Filters Sidebar -->
            <div class="md:col-span-1">
                <div class="md:sticky md:top-24 space-y-6">
                    <!-- Sort -->
                    <div class="bg-white rounded-xl p-4 shadow-sm">
                        <h3 class="text-gray-900 mb-4 font-semibold">Сортировка</h3>
                        <select
                            id="sort-select"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#38b000]"
                        >
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>По популярности</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>По рейтингу</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Сначала дешёвые</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Сначала дорогие</option>
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div class="bg-white rounded-xl p-4 shadow-sm">
                        <h3 class="text-gray-900 mb-4 font-semibold">Цена за ночь</h3>
                        <form method="GET" action="{{ route('hotels.index') }}" class="space-y-3">
                            <input
                                type="number"
                                name="min_price"
                                value="{{ request('min_price') }}"
                                placeholder="От"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#38b000]"
                            />
                            <input
                                type="number"
                                name="max_price"
                                value="{{ request('max_price') }}"
                                placeholder="До"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#38b000]"
                            />
                            @if(request('city'))
                                <input type="hidden" name="city" value="{{ request('city') }}">
                            @endif
                            <button type="submit" class="w-full px-4 py-2 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8c00] transition-colors">
                                Применить
                            </button>
                        </form>
                    </div>

                    <!-- Stars -->
                    <div class="bg-white rounded-xl p-4 shadow-sm">
                        <h3 class="text-gray-900 mb-4 font-semibold">Количество звезд</h3>
                        <form method="GET" action="{{ route('hotels.index') }}" id="stars-form" class="space-y-2">
                            @foreach([5, 4, 3, 2, 1] as $star)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="stars[]"
                                        value="{{ $star }}"
                                        {{ in_array($star, (array)request('stars', [])) ? 'checked' : '' }}
                                        onchange="document.getElementById('stars-form').submit()"
                                        class="w-4 h-4 text-[#38b000] border-gray-300 rounded focus:ring-[#38b000]"
                                    />
                                    <div class="flex items-center gap-1">
                                        @for($i = 0; $i < $star; $i++)
                                            <svg class="w-4 h-4 fill-yellow-400 text-yellow-400" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-1.07 3.927a1 1 0 01-.39 1.18l-3.462 2.462c-.969.69-2.157-.38-1.902-1.81l1.07-3.292a1 1 0 00-.95-.69H5.577c-.969 0-1.371-1.24-.588-1.81l3.462-2.462a1 1 0 01.39-1.18z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </label>
                            @endforeach
                            @if(request('city'))
                                <input type="hidden" name="city" value="{{ request('city') }}">
                            @endif
                        </form>
                    </div>

                    <a
                        href="{{ route('hotels.index') }}"
                        class="w-full block px-4 py-2 text-center text-[#38b000] border border-[#38b000] rounded-lg hover:bg-[#38b000] hover:text-white transition-colors"
                    >
                        Сбросить фильтры
                    </a>
                </div>
            </div>

            <!-- Hotels List -->
            <div class="md:col-span-3">
                @if($hotels->count() === 0)
                    <div class="text-center py-16">
                        <p class="text-gray-500 mb-4">Отели не найдены</p>
                        <a
                            href="{{ route('hotels.index') }}"
                            class="inline-block px-6 py-2 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8c00] transition-colors"
                        >
                            Сбросить фильтры
                        </a>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($hotels as $hotel)
                            <div
                                onclick="window.location='{{ route('hotels.show', $hotel) }}'"
                                class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all cursor-pointer overflow-hidden group"
                            >
                                <div class="flex flex-col md:flex-row">
                                    <!-- Image -->
                                    <div class="relative md:w-72 h-56 md:h-auto flex-shrink-0">
                                        @if($hotel->image)
                                            <img
                                                src="{{ asset('storage/' . $hotel->image) }}"
                                                alt="{{ $hotel->name }}"
                                                class="w-full h-full object-cover"
                                            />
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        @auth
                                            <button
                                                onclick="event.stopPropagation(); toggleFavorite({{ $hotel->id }})"
                                                class="absolute top-4 right-4 w-10 h-10 bg-white rounded-full flex items-center justify-center hover:scale-110 transition-transform shadow-lg"
                                            >
                                                <svg class="w-5 h-5 {{ $hotel->isFavoritedBy(auth()->id()) ? 'fill-red-500 text-red-500' : 'text-gray-400' }}"
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                            </button>
                                        @endauth
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 p-6">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    @if($hotel->stars)
                                                        <div class="flex items-center gap-1">
                                                            @for($i = 0; $i < $hotel->stars; $i++)
                                                                <svg class="w-4 h-4 fill-yellow-400 text-yellow-400" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-1.07 3.927a1 1 0 01-.39 1.18l-3.462 2.462c-.969.69-2.157-.38-1.902-1.81l1.07-3.292a1 1 0 00-.95-.69H5.577c-.969 0-1.371-1.24-.588-1.81l3.462-2.462a1 1 0 01.39-1.18z"/>
                                                                </svg>
                                                            @endfor
                                                        </div>
                                                    @endif
                                                    <span class="text-sm text-gray-600">Отель</span>
                                                </div>
                                                <h3 class="text-gray-900 mb-2 text-xl font-semibold">{{ $hotel->name }}</h3>
                                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    <span>{{ $hotel->city }} · {{ $hotel->address }}</span>
                                                </div>
                                            </div>
                                            
                                            @if($hotel->rating)
                                                <div class="flex items-center gap-2 bg-gray-900 text-white px-3 py-1 rounded-lg ml-4">
                                                    <span class="text-lg font-semibold">{{ number_format($hotel->rating, 1) }}</span>
                                                    <div class="text-xs">
                                                        <div>{{ $hotel->rating >= 4.5 ? 'Отлично' : ($hotel->rating >= 4 ? 'Хорошо' : 'Нормально') }}</div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        @if($hotel->description)
                                            <p class="text-sm text-gray-600 line-clamp-2 mb-4">
                                                {{ $hotel->description }}
                                            </p>
                                        @endif

                                        <!-- Rooms info -->
                                        @if($hotel->rooms->count() > 0)
                                            <div class="mb-4">
                                                <div class="text-sm text-gray-600 mb-2">
                                                    Доступно номеров: {{ $hotel->rooms->count() }}
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Price -->
                                        <div class="flex items-end justify-between">
                                            <div class="text-sm text-gray-600">
                                                Цена за ночь от
                                            </div>
                                            <div class="text-right">
                                                <div class="text-2xl text-gray-900 font-bold">
                                                    {{ number_format($hotel->min_price, 0) }} ₸
                                                </div>
                                                @if($hotel->rooms->count() > 0)
                                                    <div class="text-xs text-gray-500">
                                                        {{ $hotel->rooms->count() }} {{ $hotel->rooms->count() == 1 ? 'номер' : 'номеров' }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($hotels->hasPages())
                        <div class="mt-8">
                            {{ $hotels->links() }}
                        </div>
                    @endif
                @endif
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

<script>
document.getElementById('sort-select').addEventListener('change', function() {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', this.value);
    window.location.href = url.toString();
});
</script>
@endsection
