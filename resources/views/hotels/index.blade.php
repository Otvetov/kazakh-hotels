@extends('layouts.app')

@section('title', 'Каталог отелей - Kazakh Hotels')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Horizontal search bar --}}
    <form action="{{ route('hotels.index') }}" method="GET" id="hotelsSearchForm">
        <input type="hidden" name="sort" id="sortInput" value="{{ request('sort', 'popular') }}">
        <div class="otl-surface p-3 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-[1fr_1fr_1fr_auto] gap-2">
                {{-- City --}}
                <button type="button" onclick="openModal('searchModal')" class="search-btn flex items-center gap-3 p-3 bg-[#141516] border border-white/10 rounded-2xl hover:border-[#8ee30f] transition-colors text-left">
                    <svg class="w-5 h-5 text-[#8ee30f] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span id="cityValueHotels" class="value truncate">{{ request('city') ?: 'Выберите направление' }}</span>
                </button>
                <input type="hidden" name="city" id="cityInputHotels" value="{{ request('city') }}">

                {{-- Dates --}}
                <button type="button" onclick="openModal('dateModal')" class="search-btn flex items-center gap-3 p-3 bg-[#141516] border border-white/10 rounded-2xl hover:border-[#8ee30f] transition-colors text-left">
                    <svg class="w-5 h-5 text-[#8ee30f] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span id="dateValueHotels" class="value truncate">
                        @if(request('check_in') && request('check_out'))
                            {{ \Carbon\Carbon::parse(request('check_in'))->format('d M') }} – {{ \Carbon\Carbon::parse(request('check_out'))->format('d M') }}
                        @else
                            Заезд – Выезд
                        @endif
                    </span>
                </button>
                <input type="hidden" name="check_in" id="checkInInputHotels" value="{{ request('check_in') }}">
                <input type="hidden" name="check_out" id="checkOutInputHotels" value="{{ request('check_out') }}">

                {{-- Guests --}}
                <button type="button" onclick="openModal('guestsModal')" class="search-btn flex items-center gap-3 p-3 bg-[#141516] border border-white/10 rounded-2xl hover:border-[#8ee30f] transition-colors text-left">
                    <svg class="w-5 h-5 text-[#8ee30f] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span id="guestsValueHotels" class="value truncate">
                        @php $guests = request('guests', 2); $rooms = request('rooms', 1); @endphp
                        {{ $guests }} {{ $guests == 1 ? 'гость' : 'гостей' }}, {{ $rooms }} {{ $rooms == 1 ? 'номер' : 'номеров' }}
                    </span>
                </button>
                <input type="hidden" name="guests" id="guestsInputHotels" value="{{ request('guests', 2) }}">
                <input type="hidden" name="rooms" id="roomsInputHotels" value="{{ request('rooms', 1) }}">

                <button type="submit" class="btn-accent px-8 py-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span>Найти</span>
                </button>
            </div>
            <div id="searchErrorHotels" class="hidden mt-3 p-3 bg-[#f04141]/10 border border-[#f04141]/30 rounded-xl text-sm text-[#ff8a8a]"></div>
        </div>
    </form>

    {{-- Sort chips --}}
    @php
        $currentSort = request('sort', 'popular');
        $sorts = ['popular' => 'Популярные', 'rating' => 'По рейтингу', 'price_asc' => 'Сначала дешёвые', 'price_desc' => 'Сначала дорогие'];
    @endphp
    <div class="flex flex-wrap gap-2 mb-6">
        @foreach($sorts as $key => $label)
            <button type="button" onclick="applySort('{{ $key }}')"
                class="px-4 py-2 rounded-full text-sm font-medium transition {{ $currentSort === $key ? 'bg-[#8ee30f] text-[#0a0a0a]' : 'bg-[#1b1c1d] text-gray-300 hover:bg-[#2a2b2c]' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Header --}}
    <div class="mb-5">
        <h1 class="text-white text-2xl font-extrabold mb-1">
            {{ request('city') ? 'Отели в городе ' . request('city') : 'Все отели' }}
        </h1>
        <p class="text-[#7e8488]">
            Найдено {{ $hotels->total() }} {{ $hotels->total() == 1 ? 'отель' : 'отелей' }}
        </p>
    </div>

    @if($hotels->count() === 0)
        <div class="otl-surface text-center py-16 px-6">
            <p class="text-[#7e8488] mb-4">Отели не найдены</p>
            <a href="{{ route('hotels.index') }}" class="btn-accent px-6 py-2.5">Сбросить фильтры</a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($hotels as $hotel)
                <div onclick="window.location='{{ route('hotels.show', $hotel) }}'"
                     class="otl-surface overflow-hidden cursor-pointer hover:ring-2 hover:ring-[#8ee30f]/40 transition group">
                    <div class="flex flex-col md:flex-row">
                        {{-- Image --}}
                        <div class="relative md:w-80 h-56 md:h-auto flex-shrink-0">
                            @if($hotel->image)
                                <img src="{{ $hotel->image_url }}" alt="{{ $hotel->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-[#141516]">
                                    <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                </div>
                            @endif
                            @auth
                                <button onclick="event.stopPropagation(); toggleFavorite({{ $hotel->id }})"
                                        class="absolute top-3 right-3 p-2 bg-black/40 backdrop-blur-sm rounded-full hover:bg-black/60 transition">
                                    <svg class="w-5 h-5 {{ $hotel->isFavoritedBy(auth()->id()) ? 'fill-current text-[#f04141]' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            @endauth
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 p-6 flex flex-col">
                            <div class="flex items-start justify-between gap-4 mb-2">
                                <div class="min-w-0">
                                    <h3 class="text-white text-xl font-bold mb-1">{{ $hotel->name }}</h3>
                                    <div class="flex items-center gap-1.5 text-sm text-[#7e8488]">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="truncate">{{ $hotel->city }} · {{ $hotel->address }}</span>
                                    </div>
                                </div>
                                @if($hotel->rating)
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <div class="text-right hidden sm:block">
                                            <div class="text-sm text-white font-medium">{{ $hotel->rating >= 4.5 ? 'Отлично' : ($hotel->rating >= 4 ? 'Хорошо' : 'Неплохо') }}</div>
                                        </div>
                                        <span class="bg-[#8ee30f] text-[#0a0a0a] px-2.5 py-1 rounded-lg font-bold">{{ number_format($hotel->rating, 1) }}</span>
                                    </div>
                                @endif
                            </div>

                            @if($hotel->description)
                                <p class="text-sm text-[#7e8488] line-clamp-2 mb-4">{{ $hotel->description }}</p>
                            @endif

                            <div class="mt-auto flex items-end justify-between pt-3 border-t border-white/5">
                                <div class="text-sm text-[#7e8488]">
                                    @if($hotel->rooms->count() > 0)
                                        {{ $hotel->rooms->count() }} {{ $hotel->rooms->count() == 1 ? 'номер' : 'номеров' }} · цена за ночь от
                                    @else
                                        нет доступных номеров
                                    @endif
                                </div>
                                <div class="text-2xl text-[#8ee30f] font-extrabold">
                                    от {{ number_format($hotel->min_price, 0, '.', ' ') }} ₸
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($hotels->hasPages())
            <div class="mt-8">
                {{ $hotels->links() }}
            </div>
        @endif
    @endif
</div>

{{-- MODALS --}}
@include('partials.modal-search')
@include('partials.modal-dates')
@include('partials.modal-guests')

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
    .then(data => { location.reload(); });
}
</script>
@endauth

<script>
function applySort(sort) {
    const params = new URLSearchParams(window.location.search);
    params.set('sort', sort);
    window.location.search = params.toString();
}

document.addEventListener('DOMContentLoaded', function() {
    const hotelsSearchForm = document.getElementById('hotelsSearchForm');
    const searchErrorHotels = document.getElementById('searchErrorHotels');

    if (hotelsSearchForm) {
        hotelsSearchForm.addEventListener('submit', function(e) {
            const city = document.getElementById('cityInputHotels')?.value.trim();
            const checkIn = document.getElementById('checkInInputHotels')?.value;
            const checkOut = document.getElementById('checkOutInputHotels')?.value;

            const errors = [];
            if (!city || city === 'Выберите направление') errors.push('Выберите направление');
            if (!checkIn) errors.push('Выберите дату заезда');
            if (!checkOut) errors.push('Выберите дату выезда');

            if (errors.length > 0) {
                e.preventDefault();
                if (searchErrorHotels) {
                    searchErrorHotels.textContent = 'Пожалуйста, заполните следующие поля: ' + errors.join(', ');
                    searchErrorHotels.classList.remove('hidden');
                    searchErrorHotels.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
                return false;
            } else if (searchErrorHotels) {
                searchErrorHotels.classList.add('hidden');
            }
        });
    }
});
</script>

@include('partials.modals-js')
@endsection
