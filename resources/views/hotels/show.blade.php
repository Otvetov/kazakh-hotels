@extends('layouts.app')

@section('title', $hotel->name . ' - Kazakh Hotels')

@section('content')
{{-- Sticky sub-navigation --}}
<div class="sticky top-16 z-40 bg-black/95 backdrop-blur border-b border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-14">
            <div class="flex items-center gap-1 overflow-x-auto">
                <button onclick="window.history.back()" class="p-2 rounded-full hover:bg-white/10 transition mr-1 flex-shrink-0" aria-label="Назад">
                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <a href="#hero" class="px-3 py-2 text-sm text-gray-300 hover:text-white whitespace-nowrap transition">Главное</a>
                <a href="#rooms" class="px-3 py-2 text-sm text-gray-300 hover:text-white whitespace-nowrap transition">Номера</a>
                <a href="#reviews" class="px-3 py-2 text-sm text-gray-300 hover:text-white whitespace-nowrap transition">Отзывы</a>
                @if($hotel->description)
                    <a href="#about" class="px-3 py-2 text-sm text-gray-300 hover:text-white whitespace-nowrap transition">Описание</a>
                @endif
            </div>
            <div class="flex items-center gap-1 flex-shrink-0">
                <button onclick="shareHotel()" class="p-2 rounded-full hover:bg-white/10 transition" aria-label="Поделиться">
                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                    </svg>
                </button>
                @auth
                    <button onclick="toggleFavorite({{ $hotel->id }})" class="p-2 rounded-full hover:bg-white/10 transition" aria-label="В избранное">
                        <svg class="w-5 h-5 {{ $isFavorited ? 'fill-current text-[#f04141]' : 'text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>
                @endauth
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Hero --}}
    <div id="hero" class="otl-surface overflow-hidden mb-6 scroll-mt-32">
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
        </div>

        <div class="p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                <div class="min-w-0">
                    {{-- Badges --}}
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        @if($hotel->rating)
                            <span class="flex items-center gap-1.5 bg-[#8ee30f] text-[#0a0a0a] px-2.5 py-1 rounded-lg font-bold">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.37 4.24a1 1 0 00.95.69h4.46c.97 0 1.37 1.24.59 1.81l-3.61 2.62a1 1 0 00-.36 1.12l1.38 4.24c.3.92-.75 1.69-1.54 1.12l-3.6-2.62a1 1 0 00-1.18 0l-3.6 2.62c-.79.57-1.84-.2-1.54-1.12l1.38-4.24a1 1 0 00-.36-1.12L1.33 9.67c-.78-.57-.38-1.81.59-1.81h4.46a1 1 0 00.95-.69z"/></svg>
                                {{ number_format($hotel->rating, 1) }}
                            </span>
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
                        @if($hotel->rooms->where('is_available', true)->count())
                            <span class="chip">Есть свободные</span>
                        @endif
                    </div>
                </div>

                {{-- Price + CTA --}}
                <div class="flex-shrink-0 md:text-right">
                    <div class="text-[#7e8488] text-sm mb-1">Цена за ночь от</div>
                    <div class="text-3xl font-extrabold text-[#8ee30f] mb-3">{{ number_format($hotel->min_price, 0, '.', ' ') }} ₸</div>
                    <a href="#rooms" class="btn-accent px-8 py-3">Выбрать номер</a>
                </div>
            </div>
        </div>
    </div>

    {{-- AI assistant (заготовка под будущего ИИ-помощника) --}}
    <div class="otl-surface p-6 md:p-8 mb-6">
        <div class="flex items-center gap-2 mb-1">
            <h2 class="text-white text-xl font-extrabold">Уточните до бронирования</h2>
            <svg class="w-5 h-5 text-[#c084fc]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l1.9 5.6L19.5 9l-4.5 3.3L16.8 18 12 14.7 7.2 18l1.8-5.7L4.5 9l5.6-1.4L12 2z"/></svg>
        </div>
        <p class="text-[#7e8488] mb-5">ИИ-помощник подскажет за пару секунд</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            @php
                $fluent = 'https://cdn.jsdelivr.net/gh/microsoft/fluentui-emoji@main/assets';
                $aiQuestions = [
                    [$fluent.'/Croissant/3D/croissant_3d.png', 'В отеле есть завтраки?'],
                    [$fluent.'/Automobile/3D/automobile_3d.png', 'Есть ли парковка?'],
                    [$fluent.'/Alarm%20clock/3D/alarm_clock_3d.png', 'Во сколько заезд и выезд?'],
                    [$fluent.'/Dog%20face/3D/dog_face_3d.png', 'Можно заехать с питомцем?'],
                ];
            @endphp
            @foreach($aiQuestions as [$icon, $q])
                <button type="button" onclick="askAI()" class="text-left bg-[#141516] rounded-2xl p-4 hover:bg-[#1f2021] transition">
                    <img src="{{ $icon }}" alt="" class="w-10 h-10 mb-3" loading="lazy">
                    <div class="text-sm text-white">{{ $q }}</div>
                </button>
            @endforeach
        </div>

        <button type="button" onclick="askAI()" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#c084fc]/15 text-[#c084fc] hover:bg-[#c084fc]/25 transition font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Спросить про другое
        </button>

        <div id="aiNote" class="hidden mt-4 p-3 bg-[#c084fc]/10 border border-[#c084fc]/30 rounded-xl text-sm text-[#d8b4fe]">
            ИИ-помощник скоро появится на сайте — следите за обновлениями ✨
        </div>
    </div>

    {{-- Rooms --}}
    <div id="rooms" class="mb-8 scroll-mt-32">
        <h2 class="text-white text-xl font-extrabold mb-1">Доступные номера</h2>
        <p class="text-[#7e8488] mb-5">{{ $hotel->rooms->count() }} {{ $hotel->rooms->count() == 1 ? 'вариант размещения' : 'вариантов размещения' }}</p>

        @if($hotel->rooms->count() === 0)
            <div class="otl-surface p-8 text-center">
                <p class="text-[#7e8488]">Нет доступных номеров</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($hotel->rooms as $room)
                    <div class="otl-surface overflow-hidden flex flex-col md:flex-row">
                        {{-- Image --}}
                        <div class="md:w-64 h-48 md:h-auto flex-shrink-0 bg-[#141516]">
                            @if($room->image)
                                <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Details --}}
                        <div class="flex-1 p-5 min-w-0">
                            <h3 class="text-white text-lg font-semibold mb-3">{{ $room->name }}</h3>
                            <div class="flex flex-wrap gap-2">
                                <span class="chip">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    До {{ $room->capacity }} {{ $room->capacity == 1 ? 'гостя' : 'гостей' }}
                                </span>
                                <span class="chip {{ $room->is_available ? '' : 'opacity-60' }}">
                                    {{ $room->is_available ? 'Свободен' : 'Занят' }}
                                </span>
                            </div>
                        </div>

                        {{-- Price + CTA --}}
                        <div class="md:w-56 p-5 md:border-l border-white/5 flex md:flex-col items-center md:items-end justify-between gap-3">
                            <div class="md:text-right">
                                <div class="text-xl font-bold text-[#8ee30f]">{{ number_format($room->price_per_night, 0, '.', ' ') }} ₸</div>
                                <div class="text-sm text-[#7e8488]">за ночь</div>
                            </div>
                            @auth
                                <button onclick="openBookingModal({{ $room->id }})" class="btn-accent px-6 py-2.5 w-full md:w-auto">Забронировать</button>
                            @else
                                <a href="{{ route('login') }}" class="btn-accent px-6 py-2.5 w-full md:w-auto text-center">Войти</a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Reviews --}}
    <div id="reviews" class="scroll-mt-32 mb-6">
        @include('partials.review-section', ['hotel' => $hotel])
    </div>

    {{-- About --}}
    @if($hotel->description)
        <div id="about" class="otl-surface p-6 md:p-8 scroll-mt-32">
            <h2 class="text-white text-xl font-extrabold mb-4">Описание</h2>
            <p class="text-gray-300 leading-relaxed">{{ $hotel->description }}</p>
        </div>
    @endif
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
            <div id="bookingError" class="hidden p-3 bg-[#f04141]/10 border border-[#f04141]/30 rounded-xl text-sm text-[#ff8a8a]"></div>
            <div class="flex gap-3 pt-2">
                <button onclick="proceedToBooking()" class="btn-accent flex-1 py-3">Продолжить</button>
                <button onclick="closeBookingModal()" class="btn-dark px-6 py-3">Отмена</button>
            </div>
        </div>
    </div>
</div>
@endauth

<script>
function askAI() {
    const note = document.getElementById('aiNote');
    if (note) {
        note.classList.remove('hidden');
        note.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

function shareHotel() {
    const url = window.location.href;
    if (navigator.share) {
        navigator.share({ title: document.title, url: url }).catch(() => {});
    } else if (navigator.clipboard) {
        navigator.clipboard.writeText(url);
    }
}

function toggleFavorite(hotelId) {
    fetch(`/favorite/${hotelId}/toggle`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(() => location.reload())
    .catch(error => { console.error('Error:', error); location.reload(); });
}

@auth
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
    hideBookingError();
}

function showBookingError(message) {
    const el = document.getElementById('bookingError');
    if (el) { el.textContent = message; el.classList.remove('hidden'); }
}
function hideBookingError() {
    const el = document.getElementById('bookingError');
    if (el) el.classList.add('hidden');
}

function proceedToBooking() {
    const checkIn = document.getElementById('bookingCheckIn').value;
    const checkOut = document.getElementById('bookingCheckOut').value;

    if (!checkIn || !checkOut) { showBookingError('Пожалуйста, выберите даты заезда и выезда'); return; }
    if (new Date(checkOut) <= new Date(checkIn)) { showBookingError('Дата выезда должна быть позже даты заезда'); return; }
    if (!selectedRoomId) { showBookingError('Ошибка: номер не выбран'); return; }

    hideBookingError();
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
@endauth
</script>

<style>
.bmodal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.7);
    align-items: center;
    justify-content: center;
    z-index: 60;
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
@endsection
