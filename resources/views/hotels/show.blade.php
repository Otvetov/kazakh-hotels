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
                        src="{{ $hotel->image_url }}"
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
                                    <button onclick="openBookingModal({{ $room->id }})" 
                                            class="w-full py-2 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8c00] transition-colors">
                                        Забронировать
                                    </button>
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

{{-- Booking Date Modal --}}
@auth
<div id="bookingDateModal" class="modal hidden" style="display: none;">
    <div class="modal-box" style="max-width: 42rem;">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-gray-900 text-xl font-bold">Выберите даты проживания</h2>
            <button onclick="closeBookingModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Дата заезда</label>
                <input
                    type="date"
                    id="bookingCheckIn"
                    min="{{ date('Y-m-d') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#38b000] focus:border-transparent"
                />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Дата выезда</label>
                <input
                    type="date"
                    id="bookingCheckOut"
                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#38b000] focus:border-transparent"
                />
            </div>

            <div class="flex gap-3 pt-4">
                <button
                    onclick="proceedToBooking()"
                    class="flex-1 py-3 bg-[#38b000] text-white rounded-xl hover:bg-[#2d8c00] transition font-semibold"
                >
                    Продолжить
                </button>
                <button
                    onclick="closeBookingModal()"
                    class="px-6 py-3 text-gray-700 border-2 border-gray-300 rounded-xl hover:border-gray-400 transition"
                >
                    Отмена
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let selectedRoomId = null;

function openBookingModal(roomId) {
    selectedRoomId = roomId;
    const modal = document.getElementById('bookingDateModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeBookingModal() {
    const modal = document.getElementById('bookingDateModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
    selectedRoomId = null;
    document.getElementById('bookingCheckIn').value = '';
    document.getElementById('bookingCheckOut').value = '';
}

function proceedToBooking() {
    const checkIn = document.getElementById('bookingCheckIn').value;
    const checkOut = document.getElementById('bookingCheckOut').value;

    if (!checkIn || !checkOut) {
        alert('Пожалуйста, выберите даты заезда и выезда');
        return;
    }

    if (new Date(checkOut) <= new Date(checkIn)) {
        alert('Дата выезда должна быть позже даты заезда');
        return;
    }

    if (!selectedRoomId) {
        alert('Ошибка: номер не выбран');
        return;
    }

    // Redirect to booking page with parameters
    const url = new URL('{{ route("bookings.create") }}', window.location.origin);
    url.searchParams.set('room_id', selectedRoomId);
    url.searchParams.set('check_in', checkIn);
    url.searchParams.set('check_out', checkOut);
    
    window.location.href = url.toString();
}

// Close modal on outside click
document.getElementById('bookingDateModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeBookingModal();
    }
});

// Update check-out min date when check-in changes
document.getElementById('bookingCheckIn')?.addEventListener('change', function() {
    if (this.value) {
        const minDate = new Date(this.value);
        minDate.setDate(minDate.getDate() + 1);
        const checkOut = document.getElementById('bookingCheckOut');
        checkOut.min = minDate.toISOString().split('T')[0];
        if (checkOut.value && checkOut.value <= this.value) {
            checkOut.value = '';
        }
    }
});

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

<style>
.modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    align-items: center;
    justify-content: center;
    z-index: 50;
    padding: 1rem;
}

.modal.hidden {
    display: none !important;
}

.modal:not(.hidden) {
    display: flex;
}

.modal-box {
    background: white;
    border-radius: 16px;
    width: 100%;
    max-width: 400px;
    max-height: 90vh;
    overflow-y: auto;
    animation: modalFadeIn 0.2s ease-out;
}

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
</style>
@endauth
@endsection

