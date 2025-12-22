@extends('layouts.app')

@section('title', 'Kazakh Hotels - Отели для путешествий по Казахстану')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors">
    <div class="flex flex-col lg:flex-row max-w-7xl mx-auto px-4 py-8 gap-8">
        <!-- Right Side - Fixed Search Panel (shown first on mobile, last on desktop) -->
        <div class="lg:order-2 w-full lg:w-[400px] lg:flex-shrink-0">
            <div class="lg:sticky lg:top-24">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 transition-colors">
                    <h2 class="text-gray-900 dark:text-white text-xl font-semibold mb-6">Найдите отель</h2>
                    
                    <form action="{{ route('hotels.index') }}" method="GET" class="space-y-4">
                        <!-- City/Hotel Search -->
                        <button
                            type="button"
                            onclick="openSearchModal()"
                            class="w-full flex items-start gap-3 p-4 border border-gray-300 dark:border-gray-600 rounded-xl hover:border-[#0066cc] transition-colors text-left bg-white dark:bg-gray-700"
                        >
                            <svg class="w-5 h-5 text-[#0066cc] flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Город, отель или направление</div>
                                <div id="selected-city" class="text-gray-900 dark:text-white truncate">{{ request('city') ?: 'Выберите направление' }}</div>
                                <input type="hidden" name="city" id="city-input" value="{{ request('city') }}">
                            </div>
                        </button>

                        <!-- Date Picker -->
                        <button
                            type="button"
                            onclick="openDateModal()"
                            class="w-full flex items-start gap-3 p-4 border border-gray-300 dark:border-gray-600 rounded-xl hover:border-[#0066cc] transition-colors text-left bg-white dark:bg-gray-700"
                        >
                            <svg class="w-5 h-5 text-[#0066cc] flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Выберите даты</div>
                                <div id="selected-dates" class="text-gray-900 dark:text-white truncate">
                                    @if(request('check_in') && request('check_out'))
                                        {{ \Carbon\Carbon::parse(request('check_in'))->format('d M') }} - {{ \Carbon\Carbon::parse(request('check_out'))->format('d M') }}
                                    @else
                                        Заезд - Выезд
                                    @endif
                                </div>
                                <input type="hidden" name="check_in" id="check-in-input" value="{{ request('check_in') }}">
                                <input type="hidden" name="check_out" id="check-out-input" value="{{ request('check_out') }}">
                            </div>
                        </button>

                        <!-- Guests Picker -->
                        <button
                            type="button"
                            onclick="openGuestsModal()"
                            class="w-full flex items-start gap-3 p-4 border border-gray-300 dark:border-gray-600 rounded-xl hover:border-[#0066cc] transition-colors text-left bg-white dark:bg-gray-700"
                        >
                            <svg class="w-5 h-5 text-[#0066cc] flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Гости и номера</div>
                                <div id="selected-guests" class="text-gray-900 dark:text-white truncate">
                                    @php
                                        $guests = request('guests', 2);
                                        $rooms = request('rooms', 1);
                                    @endphp
                                    {{ $guests }} {{ $guests == 1 ? 'гость' : 'гостей' }}, {{ $rooms }} {{ $rooms == 1 ? 'номер' : 'номеров' }}
                                </div>
                                <input type="hidden" name="guests" id="guests-input" value="{{ request('guests', 2) }}">
                                <input type="hidden" name="rooms" id="rooms-input" value="{{ request('rooms', 1) }}">
                            </div>
                        </button>

                        <!-- Search Button -->
                        <button
                            type="submit"
                            class="w-full flex items-center justify-center gap-2 p-4 bg-[#0066cc] text-white rounded-xl hover:bg-[#0052a3] transition-colors font-medium"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span>Найти</span>
                        </button>
                    </form>

                    <!-- Additional Info -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 space-y-3">
                        <div class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <span class="text-[#0066cc]">✓</span>
                            <span>Большой выбор отелей</span>
                        </div>
                        <div class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <span class="text-[#0066cc]">✓</span>
                            <span>Отели по всему Казахстану</span>
                        </div>
                        <div class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <span class="text-[#0066cc]">✓</span>
                            <span>Лучшие цены и условия</span>
                        </div>
                        <div class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <span class="text-[#0066cc]">✓</span>
                            <span>Поддержка 24/7</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Left Side - Scrollable Hotel Cards (shown second on mobile, first on desktop) -->
        <div class="lg:order-1 flex-1 overflow-y-auto">
            <h2 class="text-gray-900 dark:text-white text-2xl font-bold mb-6">Идеи для путешествий по Казахстану</h2>
            
            <div id="hotels-container" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                @include('partials.hotel-cards', ['hotels' => $hotels])
            </div>

            @if($hotels->hasMorePages())
                <div class="text-center pb-8">
                    <button
                        id="load-more"
                        class="px-8 py-3 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:border-[#0066cc] hover:text-[#0066cc] transition-colors"
                    >
                        Загрузить ещё
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Search Modal -->
<div id="search-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full p-6 max-h-[80vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Выберите город</h3>
            <button onclick="closeSearchModal()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <input type="text" id="city-search" placeholder="Поиск города..." 
               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg mb-4 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        <div id="cities-list" class="space-y-2 max-h-64 overflow-y-auto">
            @php
                $cities = \App\Models\Hotel::distinct()->pluck('city')->sort();
            @endphp
            @foreach($cities as $cityName)
                <button onclick="selectCity('{{ $cityName }}')" 
                        class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg text-gray-900 dark:text-white">
                    {{ $cityName }}
                </button>
            @endforeach
        </div>
    </div>
</div>

<!-- Date Picker Modal -->
<div id="date-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Выберите даты</h3>
            <button onclick="closeDateModal()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Заезд</label>
                <input type="date" id="check-in-date" min="{{ date('Y-m-d') }}" 
                       value="{{ request('check_in') }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Выезд</label>
                <input type="date" id="check-out-date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                       value="{{ request('check_out') }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>
        </div>
        <button onclick="selectDates()" class="w-full px-4 py-3 bg-[#0066cc] text-white rounded-xl hover:bg-[#0052a3] transition-colors">
            Применить
        </button>
    </div>
</div>

<!-- Guests Modal -->
<div id="guests-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Гости и номера</h3>
            <button onclick="closeGuestsModal()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <div>
                    <div class="font-medium text-gray-900 dark:text-white">Гости</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Количество гостей</div>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="changeGuests(-1)" class="w-8 h-8 rounded-full border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700">-</button>
                    <span id="guests-count" class="w-12 text-center font-medium text-gray-900 dark:text-white">{{ request('guests', 2) }}</span>
                    <button onclick="changeGuests(1)" class="w-8 h-8 rounded-full border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700">+</button>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <div>
                    <div class="font-medium text-gray-900 dark:text-white">Номера</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Количество номеров</div>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="changeRooms(-1)" class="w-8 h-8 rounded-full border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700">-</button>
                    <span id="rooms-count" class="w-12 text-center font-medium text-gray-900 dark:text-white">{{ request('rooms', 1) }}</span>
                    <button onclick="changeRooms(1)" class="w-8 h-8 rounded-full border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700">+</button>
                </div>
            </div>
        </div>
        <button onclick="selectGuests()" class="w-full mt-6 px-4 py-3 bg-[#0066cc] text-white rounded-xl hover:bg-[#0052a3] transition-colors">
            Применить
        </button>
    </div>
</div>

<script>
let guestsCount = {{ request('guests', 2) }};
let roomsCount = {{ request('rooms', 1) }};

function openSearchModal() {
    document.getElementById('search-modal').classList.remove('hidden');
}

function closeSearchModal() {
    document.getElementById('search-modal').classList.add('hidden');
}

function selectCity(city) {
    document.getElementById('city-input').value = city;
    document.getElementById('selected-city').textContent = city;
    closeSearchModal();
}

function openDateModal() {
    document.getElementById('date-modal').classList.remove('hidden');
}

function closeDateModal() {
    document.getElementById('date-modal').classList.add('hidden');
}

function selectDates() {
    const checkIn = document.getElementById('check-in-date').value;
    const checkOut = document.getElementById('check-out-date').value;
    
    if (checkIn && checkOut) {
        document.getElementById('check-in-input').value = checkIn;
        document.getElementById('check-out-input').value = checkOut;
        
        const checkInDate = new Date(checkIn);
        const checkOutDate = new Date(checkOut);
        const options = { day: 'numeric', month: 'short' };
        
        document.getElementById('selected-dates').textContent = 
            checkInDate.toLocaleDateString('ru-RU', options) + ' - ' + 
            checkOutDate.toLocaleDateString('ru-RU', options);
    }
    
    closeDateModal();
}

function openGuestsModal() {
    document.getElementById('guests-modal').classList.remove('hidden');
}

function closeGuestsModal() {
    document.getElementById('guests-modal').classList.add('hidden');
}

function changeGuests(delta) {
    guestsCount = Math.max(1, guestsCount + delta);
    document.getElementById('guests-count').textContent = guestsCount;
}

function changeRooms(delta) {
    roomsCount = Math.max(1, roomsCount + delta);
    document.getElementById('rooms-count').textContent = roomsCount;
}

function selectGuests() {
    document.getElementById('guests-input').value = guestsCount;
    document.getElementById('rooms-input').value = roomsCount;
    
    const guestsText = guestsCount === 1 ? 'гость' : 'гостей';
    const roomsText = roomsCount === 1 ? 'номер' : 'номеров';
    document.getElementById('selected-guests').textContent = 
        `${guestsCount} ${guestsText}, ${roomsCount} ${roomsText}`;
    
    closeGuestsModal();
}

// City search filter
document.addEventListener('DOMContentLoaded', function() {
    const citySearch = document.getElementById('city-search');
    if (citySearch) {
        citySearch.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cities = document.querySelectorAll('#cities-list button');
            cities.forEach(city => {
                const cityName = city.textContent.toLowerCase();
                city.style.display = cityName.includes(searchTerm) ? 'block' : 'none';
            });
        });
    }

    // Load more hotels with animations
    const loadMoreBtn = document.getElementById('load-more');
    if (loadMoreBtn) {
        let page = 2;
        let isLoading = false;

        loadMoreBtn.addEventListener('click', function() {
            if (isLoading) return;
            
            isLoading = true;
            loadMoreBtn.disabled = true;
            const originalContent = loadMoreBtn.innerHTML;
            loadMoreBtn.innerHTML = '<span>Загрузка...</span>';

            fetch(`{{ route('hotels.index') }}?page=${page}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('hotels-container');
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.html;
                
                const newCards = Array.from(tempDiv.children);
                newCards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    container.appendChild(card);
                    
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 50);
                });
                
                if (!data.has_more || !data.html) {
                    loadMoreBtn.remove();
                } else {
                    page++;
                    isLoading = false;
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.innerHTML = originalContent;
                }
            })
            .catch(error => {
                console.error('Error loading hotels:', error);
                isLoading = false;
                loadMoreBtn.disabled = false;
                loadMoreBtn.innerHTML = originalContent;
                alert('Ошибка при загрузке отелей. Попробуйте еще раз.');
            });
        });
    }

    // Update check-out min date when check-in changes
    const checkInDate = document.getElementById('check-in-date');
    const checkOutDate = document.getElementById('check-out-date');
    if (checkInDate && checkOutDate) {
        checkInDate.addEventListener('change', function() {
            if (this.value) {
                const minDate = new Date(this.value);
                minDate.setDate(minDate.getDate() + 1);
                checkOutDate.min = minDate.toISOString().split('T')[0];
                if (checkOutDate.value && checkOutDate.value <= this.value) {
                    checkOutDate.value = '';
                }
            }
        });
    }

    // Close modals on outside click
    document.getElementById('search-modal')?.addEventListener('click', function(e) {
        if (e.target === this) closeSearchModal();
    });
    document.getElementById('date-modal')?.addEventListener('click', function(e) {
        if (e.target === this) closeDateModal();
    });
    document.getElementById('guests-modal')?.addEventListener('click', function(e) {
        if (e.target === this) closeGuestsModal();
    });
});
</script>

<style>
@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-modal {
    animation: modalFadeIn 0.2s ease-out;
}

/* Smooth scroll for hotels container */
#hotels-container {
    scroll-behavior: smooth;
}

/* Card hover effects */
#hotels-container > div {
    transition: all 0.3s ease;
}

#hotels-container > div:hover {
    transform: translateY(-4px);
}

/* Hero background animation */
@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
}

.bg-gradient-to-br {
    position: relative;
    overflow: hidden;
}

.bg-gradient-to-br::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(59, 130, 246, 0.1), rgba(147, 51, 234, 0.1));
    animation: float 6s ease-in-out infinite;
}
</style>
@endsection
