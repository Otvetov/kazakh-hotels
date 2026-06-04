@extends('layouts.app')

@section('title', $hotel->name . ' - Kazakh Hotels')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <button onclick="window.history.back()" class="flex items-center gap-2 text-[#7e8488] hover:text-white mb-6 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        <span>Назад</span>
    </button>

    {{-- Hero --}}
    <div class="otl-surface overflow-hidden mb-6">
        <div class="relative h-72 md:h-[26rem]">
            @if($hotel->image)
                <img src="{{ $hotel->image_url }}" alt="{{ $hotel->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center bg-[#141516]">
                    <svg class="w-24 h-24 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
            @endif
            @auth
                <button onclick="toggleFavorite({{ $hotel->id }})"
                        class="absolute top-5 right-5 p-2.5 bg-black/40 backdrop-blur-sm rounded-full hover:bg-black/60 transition">
                    <svg class="w-6 h-6 {{ $isFavorited ? 'fill-current text-[#f04141]' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </button>
            @endauth
        </div>

        <div class="p-6 md:p-8">
            {{-- Badges --}}
            <div class="flex flex-wrap items-center gap-2 mb-4">
                @if($hotel->rating)
                    <span class="bg-[#8ee30f] text-[#0a0a0a] px-3 py-1 rounded-lg font-bold">{{ number_format($hotel->rating, 1) }}</span>
                    <span class="text-white font-medium">{{ $hotel->rating >= 4.5 ? 'Отлично' : ($hotel->rating >= 4 ? 'Хорошо' : 'Неплохо') }}</span>
                    @if($hotel->reviews->count())
                        <span class="text-[#7e8488] text-sm">· {{ $hotel->reviews->count() }} {{ $hotel->reviews->count() == 1 ? 'отзыв' : 'отзывов' }}</span>
                    @endif
                @endif
            </div>

            <h1 class="text-white text-2xl md:text-3xl font-extrabold mb-2">{{ $hotel->name }}</h1>
            <div class="flex items-center gap-2 text-[#7e8488] mb-5">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span>{{ $hotel->city }}, {{ $hotel->address }}</span>
            </div>

            {{-- Info chips --}}
            <div class="flex flex-wrap gap-2">
                <span class="chip">{{ $hotel->city }}</span>
                <span class="chip">{{ $hotel->rooms->count() }} {{ $hotel->rooms->count() == 1 ? 'номер' : 'номеров' }}</span>
                @if($hotel->min_price)
                    <span class="chip">от {{ number_format($hotel->min_price, 0, '.', ' ') }} ₸ за ночь</span>
                @endif
            </div>

            @if($hotel->description)
                <p class="text-gray-300 leading-relaxed mt-5">{{ $hotel->description }}</p>
            @endif
        </div>
    </div>

    {{-- Rooms --}}
    <div class="mb-8">
        <h2 class="text-white text-xl font-extrabold mb-5">Доступные номера</h2>

        @if($hotel->rooms->count() === 0)
            <div class="otl-surface p-8 text-center">
                <p class="text-[#7e8488]">Нет доступных номеров</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($hotel->rooms as $room)
                    <div class="otl-surface overflow-hidden flex flex-col">
                        @if($room->image)
                            <div class="h-44 overflow-hidden">
                                <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->name }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="p-5 flex flex-col flex-1">
                            <h3 class="text-white font-semibold mb-2">{{ $room->name }}</h3>
                            <div class="flex items-center gap-2 text-sm text-[#7e8488] mb-4">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span>До {{ $room->capacity }} {{ $room->capacity == 1 ? 'гостя' : 'гостей' }}</span>
                            </div>

                            <div class="mt-auto">
                                <div class="mb-3">
                                    <span class="text-xl font-bold text-[#8ee30f]">{{ number_format($room->price_per_night, 0, '.', ' ') }} ₸</span>
                                    <span class="text-sm text-[#7e8488]">/ ночь</span>
                                </div>
                                @auth
                                    <button onclick="openBookingModal({{ $room->id }})" class="btn-accent w-full py-2.5">
                                        Забронировать
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="btn-accent w-full py-2.5">
                                        Войти для бронирования
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Reviews --}}
    @include('partials.review-section', ['hotel' => $hotel])
</div>

{{-- Booking Date Modal --}}
@auth
<div id="bookingDateModal" class="bmodal hidden" style="display: none;">
    <div class="bmodal-box">
        <div class="flex items-center justify-center relative p-6 border-b border-white/10">
            <h2 class="text-white text-lg font-bold">Выберите даты проживания</h2>
            <button onclick="closeBookingModal()" class="absolute right-5 top-1/2 -translate-y-1/2 p-2 bg-white/5 hover:bg-white/10 rounded-full transition-colors">
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">Дата заезда</label>
                <input type="date" id="bookingCheckIn" min="{{ date('Y-m-d') }}" style="color-scheme: dark;" class="field-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">Дата выезда</label>
                <input type="date" id="bookingCheckOut" min="{{ date('Y-m-d', strtotime('+1 day')) }}" style="color-scheme: dark;" class="field-input">
            </div>
            <div class="flex gap-3 pt-2">
                <button onclick="proceedToBooking()" class="btn-accent flex-1 py-3">Продолжить</button>
                <button onclick="closeBookingModal()" class="btn-dark px-6 py-3">Отмена</button>
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

    if (!checkIn || !checkOut) { alert('Пожалуйста, выберите даты заезда и выезда'); return; }
    if (new Date(checkOut) <= new Date(checkIn)) { alert('Дата выезда должна быть позже даты заезда'); return; }
    if (!selectedRoomId) { alert('Ошибка: номер не выбран'); return; }

    const url = new URL('{{ route("bookings.create") }}', window.location.origin);
    url.searchParams.set('room_id', selectedRoomId);
    url.searchParams.set('check_in', checkIn);
    url.searchParams.set('check_out', checkOut);
    window.location.href = url.toString();
}

document.getElementById('bookingDateModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeBookingModal();
});

document.getElementById('bookingCheckIn')?.addEventListener('change', function() {
    if (this.value) {
        const minDate = new Date(this.value);
        minDate.setDate(minDate.getDate() + 1);
        const checkOut = document.getElementById('bookingCheckOut');
        checkOut.min = minDate.toISOString().split('T')[0];
        if (checkOut.value && checkOut.value <= this.value) checkOut.value = '';
    }
});

function toggleFavorite(hotelId) {
    fetch(`/favorite/${hotelId}/toggle`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        const button = event.target.closest('button');
        const svg = button.querySelector('svg');
        if (data.is_favorited) {
            svg.classList.remove('text-white');
            svg.classList.add('text-[#f04141]', 'fill-current');
        } else {
            svg.classList.remove('text-[#f04141]', 'fill-current');
            svg.classList.add('text-white');
        }
    })
    .catch(error => { console.error('Error:', error); location.reload(); });
}
</script>

<style>
.bmodal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.7);
    align-items: center;
    justify-content: center;
    z-index: 50;
    padding: 1rem;
}
.bmodal.hidden { display: none !important; }
.bmodal:not(.hidden) { display: flex; }
.bmodal-box {
    background: #1b1c1d;
    color: #fafafa;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 24px;
    width: 100%;
    max-width: 420px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 24px 50px -12px rgba(0, 0, 0, 0.6);
    animation: bmodalIn 0.2s ease-out;
}
@keyframes bmodalIn {
    from { opacity: 0; transform: scale(0.97); }
    to { opacity: 1; transform: scale(1); }
}
</style>
@endauth
@endsection
