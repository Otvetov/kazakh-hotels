@extends('layouts.app')

@section('title', 'Каталог отелей - Kazakh Hotels')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-gray-900 mb-2 text-2xl font-bold">
                {{ request('city') ? 'Отели в ' . request('city') : 'Все отели' }}
            </h1>
            <p class="text-gray-600">
                Найдено {{ $hotels->total() }} {{ $hotels->total() == 1 ? 'отель' : 'отелей' }}
            </p>
        </div>

        <!-- Horizontal Search Panel -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
            <form action="{{ route('hotels.index') }}" method="GET" id="hotelsSearchForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- City Search -->
                    <div>
                        <button type="button" onclick="openModal('searchModal')" class="search-btn w-full flex items-start gap-3 p-4 border border-gray-300 rounded-xl hover:border-[#38b000] transition-colors text-left bg-white">
                            <svg class="w-5 h-5 text-[#38b000] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <span class="label text-xs text-gray-500 block mb-0.5">Город, отель или направление</span>
                                <span id="cityValueHotels" class="value text-gray-900 font-medium truncate block">{{ request('city') ?: 'Выберите направление' }}</span>
                            </div>
                        </button>
                        <input type="hidden" name="city" id="cityInputHotels" value="{{ request('city') }}">
                    </div>

                    <!-- Dates -->
                    <div>
                        <button type="button" onclick="openModal('dateModal')" class="search-btn w-full flex items-start gap-3 p-4 border border-gray-300 rounded-xl hover:border-[#38b000] transition-colors text-left bg-white">
                            <svg class="w-5 h-5 text-[#38b000] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <span class="label text-xs text-gray-500 block mb-0.5">Даты поездки</span>
                                <span id="dateValueHotels" class="value text-gray-900 font-medium truncate block">
                                    @if(request('check_in') && request('check_out'))
                                        {{ \Carbon\Carbon::parse(request('check_in'))->format('d M') }} – {{ \Carbon\Carbon::parse(request('check_out'))->format('d M') }}
                                    @else
                                        Заезд – Выезд
                                    @endif
                                </span>
                            </div>
                        </button>
                        <input type="hidden" name="check_in" id="checkInInputHotels" value="{{ request('check_in') }}">
                        <input type="hidden" name="check_out" id="checkOutInputHotels" value="{{ request('check_out') }}">
                    </div>

                    <!-- Guests -->
                    <div>
                        <button type="button" onclick="openModal('guestsModal')" class="search-btn w-full flex items-start gap-3 p-4 border border-gray-300 rounded-xl hover:border-[#38b000] transition-colors text-left bg-white">
                            <svg class="w-5 h-5 text-[#38b000] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <span class="label text-xs text-gray-500 block mb-0.5">Гости и номера</span>
                                <span id="guestsValueHotels" class="value text-gray-900 font-medium truncate block">
                                    @php
                                        $guests = request('guests', 2);
                                        $rooms = request('rooms', 1);
                                    @endphp
                                    {{ $guests }} {{ $guests == 1 ? 'гость' : 'гостей' }}, {{ $rooms }} {{ $rooms == 1 ? 'номер' : 'номеров' }}
                                </span>
                            </div>
                        </button>
                        <input type="hidden" name="guests" id="guestsInputHotels" value="{{ request('guests', 2) }}">
                        <input type="hidden" name="rooms" id="roomsInputHotels" value="{{ request('rooms', 1) }}">
                    </div>

                    <!-- Sort & Search Button -->
                    <div class="flex gap-2">
                        <select
                            id="sort-select"
                            name="sort"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#38b000] bg-white"
                        >
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>По популярности</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>По рейтингу</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Сначала дешёвые</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Сначала дорогие</option>
                        </select>
                        <button type="submit" class="px-6 py-2 bg-[#38b000] text-white rounded-xl hover:bg-[#2d8c00] transition font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="hidden md:inline">Найти</span>
                        </button>
                    </div>
                </div>

                <div id="searchErrorHotels" class="hidden p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600"></div>
            </form>
        </div>

        <!-- Hotels List -->
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
                                                src="{{ $hotel->image_url }}"
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
    .then(data => {
        location.reload();
    });
}
</script>
@endauth

<script>
// Update city value for hotels page
function selectCityAndCloseHotels(city) {
    const cityValue = document.getElementById('cityValueHotels');
    const cityInput = document.getElementById('cityInputHotels');
    if (cityValue) cityValue.textContent = city;
    if (cityInput) cityInput.value = city;
    closeModals();
}

// Update dates for hotels page
function saveDatesHotels() {
    const checkIn = document.getElementById('checkIn').value;
    const checkOut = document.getElementById('checkOut').value;
    
    if (checkIn && checkOut) {
        const checkInDate = new Date(checkIn);
        const checkOutDate = new Date(checkOut);
        
        const checkInFormatted = checkInDate.toLocaleDateString('ru-RU', { day: 'numeric', month: 'short' });
        const checkOutFormatted = checkOutDate.toLocaleDateString('ru-RU', { day: 'numeric', month: 'short' });
        
        const dateValue = document.getElementById('dateValueHotels');
        if (dateValue) {
            dateValue.textContent = `${checkInFormatted} – ${checkOutFormatted}`;
        }
        
        const checkInInput = document.getElementById('checkInInputHotels');
        const checkOutInput = document.getElementById('checkOutInputHotels');
        if (checkInInput) checkInInput.value = checkIn;
        if (checkOutInput) checkOutInput.value = checkOut;
        
        closeModals();
    }
}

// Update guests for hotels page
function saveGuestsHotels() {
    const guestsCount = window.guestsCount || 2;
    const roomsCount = window.roomsCount || 1;
    const guestsText = guestsCount === 1 ? 'гость' : 'гостей';
    const roomsText = roomsCount === 1 ? 'номер' : 'номеров';
    
    const guestsValue = document.getElementById('guestsValueHotels');
    if (guestsValue) {
        guestsValue.textContent = `${guestsCount} ${guestsText}, ${roomsCount} ${roomsText}`;
    }
    
    const guestsInput = document.getElementById('guestsInputHotels');
    const roomsInput = document.getElementById('roomsInputHotels');
    if (guestsInput) guestsInput.value = guestsCount;
    if (roomsInput) roomsInput.value = roomsCount;
    
    closeModals();
}

// Override selectCityAndClose for hotels page
const originalSelectCityAndClose = window.selectCityAndClose;
window.selectCityAndClose = function(city) {
    // Check if we're on hotels page
    if (document.getElementById('cityValueHotels')) {
        selectCityAndCloseHotels(city);
    } else if (originalSelectCityAndClose) {
        originalSelectCityAndClose(city);
    }
};

// Override saveDates for hotels page
const originalSaveDates = window.saveDates;
window.saveDates = function() {
    if (document.getElementById('dateValueHotels')) {
        saveDatesHotels();
    } else if (originalSaveDates) {
        originalSaveDates();
    }
};

// Override saveGuests for hotels page
const originalSaveGuests = window.saveGuests;
window.saveGuests = function() {
    if (document.getElementById('guestsValueHotels')) {
        saveGuestsHotels();
    } else if (originalSaveGuests) {
        originalSaveGuests();
    }
};

// Form validation for hotels page
document.addEventListener('DOMContentLoaded', function() {
    const hotelsSearchForm = document.getElementById('hotelsSearchForm');
    const searchErrorHotels = document.getElementById('searchErrorHotels');
    
    if (hotelsSearchForm) {
        hotelsSearchForm.addEventListener('submit', function(e) {
            const city = document.getElementById('cityInputHotels')?.value.trim();
            const checkIn = document.getElementById('checkInInputHotels')?.value;
            const checkOut = document.getElementById('checkOutInputHotels')?.value;
            
            const errors = [];
            
            if (!city || city === 'Выберите направление') {
                errors.push('Выберите направление');
            }
            
            if (!checkIn) {
                errors.push('Выберите дату заезда');
            }
            
            if (!checkOut) {
                errors.push('Выберите дату выезда');
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                if (searchErrorHotels) {
                    searchErrorHotels.textContent = 'Пожалуйста, заполните следующие поля: ' + errors.join(', ');
                    searchErrorHotels.classList.remove('hidden');
                    searchErrorHotels.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
                return false;
            } else {
                if (searchErrorHotels) {
                    searchErrorHotels.classList.add('hidden');
                }
            }
        });
    }

    // Sort select - submit form on change
    const sortSelect = document.getElementById('sort-select');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            document.getElementById('hotelsSearchForm')?.submit();
        });
    }
});
</script>

@include('partials.modals-js')
@endsection
