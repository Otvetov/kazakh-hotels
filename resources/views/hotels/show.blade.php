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
                <a href="#hero" class="px-3 py-2 text-sm text-gray-300 hover:text-white whitespace-nowrap transition">{{ __('messages.nav_main') }}</a>
                <a href="#rooms" class="px-3 py-2 text-sm text-gray-300 hover:text-white whitespace-nowrap transition">{{ __('messages.rooms') }}</a>
                <a href="#reviews" class="px-3 py-2 text-sm text-gray-300 hover:text-white whitespace-nowrap transition">{{ __('messages.nav_reviews') }}</a>
                @if($hotel->description)
                    <a href="#about" class="px-3 py-2 text-sm text-gray-300 hover:text-white whitespace-nowrap transition">{{ __('messages.nav_description') }}</a>
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

    {{-- Gallery --}}
    @php $gallery = $hotel->gallery; $photoCount = count($gallery); @endphp
    <div id="hero" class="mb-6 scroll-mt-32">
        @if($photoCount === 0)
            <div class="otl-surface h-72 md:h-[24rem] flex items-center justify-center">
                <svg class="w-24 h-24 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-4 md:grid-rows-2 gap-2 md:h-[24rem]">
                <button type="button" onclick="openGallery(0)" class="md:col-span-2 md:row-span-2 h-64 md:h-auto overflow-hidden rounded-2xl group">
                    <img src="{{ $gallery[0] }}" alt="{{ $hotel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                </button>
                @for($i = 1; $i <= 4; $i++)
                    @if(isset($gallery[$i]))
                        <button type="button" onclick="openGallery({{ $i }})" class="relative hidden md:block overflow-hidden rounded-2xl group">
                            <img src="{{ $gallery[$i] }}" alt="{{ $hotel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @if($i === 4 && $photoCount > 5)
                                <span class="absolute inset-0 bg-black/55 flex items-center justify-center text-white text-lg font-semibold">+{{ $photoCount - 5 }} {{ __('messages.photos') }}</span>
                            @endif
                        </button>
                    @endif
                @endfor
            </div>
            @if($photoCount > 1)
                <button type="button" onclick="openGallery(0)" class="mt-2 md:hidden btn-dark px-4 py-2 text-sm">{{ $photoCount }} {{ __('messages.photos') }}</button>
            @endif
        @endif
    </div>

    {{-- Info --}}
    <div class="otl-surface mb-6">
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
                            <span class="text-white font-medium">{{ $hotel->rating >= 4.5 ? __('messages.rating_excellent') : ($hotel->rating >= 4 ? __('messages.rating_good') : __('messages.rating_fair')) }}</span>
                            @if($hotel->reviews->count())
                                <span class="text-[#7e8488] text-sm">· {{ trans_choice('messages.reviews_count', $hotel->reviews->count(), ['count' => $hotel->reviews->count()]) }}</span>
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
                        <span class="chip">{{ trans_choice('messages.rooms_count', $hotel->rooms->count(), ['count' => $hotel->rooms->count()]) }}</span>
                        @if($hotel->rooms->where('is_available', true)->count())
                            <span class="chip">{{ __('messages.has_free_rooms') }}</span>
                        @endif
                    </div>
                </div>

                {{-- Price + CTA --}}
                <div class="flex-shrink-0 md:text-right">
                    <div class="text-[#7e8488] text-sm mb-1">{{ __('messages.price_per_night_from') }}</div>
                    <div class="text-3xl font-extrabold text-[#8ee30f] mb-3">{{ number_format($hotel->min_price, 0, '.', ' ') }} ₸</div>
                    <a href="#rooms" class="btn-accent px-8 py-3">{{ __('messages.choose_room') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- AI assistant (заготовка под будущего ИИ-помощника) --}}
    <div class="otl-surface p-6 md:p-8 mb-6">
        <div class="flex items-center gap-2 mb-1">
            <h2 class="text-white text-xl font-extrabold">{{ __('messages.ai_title') }}</h2>
            <svg class="w-5 h-5 text-[#c084fc]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l1.9 5.6L19.5 9l-4.5 3.3L16.8 18 12 14.7 7.2 18l1.8-5.7L4.5 9l5.6-1.4L12 2z"/></svg>
        </div>
        <p class="text-[#7e8488] mb-5">{{ __('messages.ai_subtitle') }}</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            @php
                $fluent = 'https://cdn.jsdelivr.net/gh/microsoft/fluentui-emoji@main/assets';
                $aiQuestions = [
                    [$fluent.'/Croissant/3D/croissant_3d.png', __('messages.ai_q1')],
                    [$fluent.'/Automobile/3D/automobile_3d.png', __('messages.ai_q2')],
                    [$fluent.'/Alarm%20clock/3D/alarm_clock_3d.png', __('messages.ai_q3')],
                    [$fluent.'/Dog%20face/3D/dog_face_3d.png', __('messages.ai_q4')],
                ];
            @endphp
            @foreach($aiQuestions as [$icon, $q])
                <button type="button" onclick="aiAsk(@js($q))" class="text-left bg-[#141516] rounded-2xl p-4 hover:bg-[#1f2021] transition">
                    <img src="{{ $icon }}" alt="" class="w-10 h-10 mb-3" loading="lazy">
                    <div class="text-sm text-white">{{ $q }}</div>
                </button>
            @endforeach
        </div>

        <button type="button" onclick="openAiChat()" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#c084fc]/15 text-[#c084fc] hover:bg-[#c084fc]/25 transition font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ __('messages.ai_ask_other') }}
        </button>
    </div>

    {{-- Rooms --}}
    <div id="rooms" class="mb-8 scroll-mt-32">
        <h2 class="text-white text-xl font-extrabold mb-1">{{ __('messages.available_rooms') }}</h2>
        <p class="text-[#7e8488] mb-5">{{ trans_choice('messages.room_options', $hotel->rooms->count(), ['count' => $hotel->rooms->count()]) }}</p>

        @if($hotel->rooms->count() === 0)
            <div class="otl-surface p-8 text-center">
                <p class="text-[#7e8488]">{{ __('messages.no_rooms') }}</p>
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
                                    {{ __('messages.up_to_guests', ['count' => $room->capacity]) }}
                                </span>
                                <span class="chip {{ $room->is_available ? '' : 'opacity-60' }}">
                                    {{ $room->is_available ? __('messages.room_free') : __('messages.room_occupied') }}
                                </span>
                            </div>
                        </div>

                        {{-- Price + CTA --}}
                        <div class="md:w-56 p-5 md:border-l border-white/5 flex md:flex-col items-center md:items-end justify-between gap-3">
                            <div class="md:text-right">
                                <div class="text-xl font-bold text-[#8ee30f]">{{ number_format($room->price_per_night, 0, '.', ' ') }} ₸</div>
                                <div class="text-sm text-[#7e8488]">{{ __('messages.per_night') }}</div>
                            </div>
                            @auth
                                <button onclick="openBookingModal({{ $room->id }})" class="btn-accent px-6 py-2.5 w-full md:w-auto">{{ __('messages.book') }}</button>
                            @else
                                <a href="{{ route('login') }}" class="btn-accent px-6 py-2.5 w-full md:w-auto text-center">{{ __('messages.login') }}</a>
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
            <h2 class="text-white text-xl font-extrabold mb-4">{{ __('messages.description') }}</h2>
            <p class="text-gray-300 leading-relaxed">{{ $hotel->description }}</p>
        </div>
    @endif
</div>

{{-- Gallery lightbox --}}
@if($photoCount > 0)
<div id="galleryLightbox" class="glb hidden">
    <button type="button" onclick="closeGallery()" class="glb-close" aria-label="Закрыть">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
    @if($photoCount > 1)
        <button type="button" onclick="galleryPrev()" class="glb-nav glb-prev" aria-label="Назад">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <button type="button" onclick="galleryNext()" class="glb-nav glb-next" aria-label="Вперёд">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
    @endif
    <img id="glbImg" class="glb-img" src="" alt="">
    <div id="glbCounter" class="glb-counter"></div>
</div>
@endif

{{-- Booking Date Modal --}}
@auth
<div id="bookingDateModal" class="bmodal hidden" style="display: none;">
    <div class="bmodal-box">
        <div class="flex items-center justify-center relative p-6 border-b border-white/10">
            <h2 class="text-white text-lg font-bold">{{ __('messages.select_stay_dates') }}</h2>
            <button onclick="closeBookingModal()" class="absolute right-5 top-1/2 -translate-y-1/2 p-2 bg-white/5 hover:bg-white/10 rounded-full transition-colors">
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6">
            {{-- Month navigation --}}
            <div class="flex items-center justify-between mb-4">
                <button type="button" onclick="bkPrevMonth()" id="bkCalPrev" class="p-2 rounded-full hover:bg-white/10 transition disabled:opacity-30 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <span id="bkCalMonthLabel" class="text-white font-semibold"></span>
                <button type="button" onclick="bkNextMonth()" class="p-2 rounded-full hover:bg-white/10 transition">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-7 gap-1 mb-2 text-center text-xs text-[#7e8488]">
                <span>{{ __('messages.wd_mon') }}</span><span>{{ __('messages.wd_tue') }}</span><span>{{ __('messages.wd_wed') }}</span><span>{{ __('messages.wd_thu') }}</span><span>{{ __('messages.wd_fri') }}</span>
                <span class="text-[#f04141]/70">{{ __('messages.wd_sat') }}</span><span class="text-[#f04141]/70">{{ __('messages.wd_sun') }}</span>
            </div>

            <div id="bkCalDays" class="grid grid-cols-7 gap-1"></div>

            <p id="bkCalHint" class="text-sm text-[#7e8488] mt-4 text-center">{{ __('messages.choose_checkin') }}</p>

            <input type="hidden" id="bookingCheckIn">
            <input type="hidden" id="bookingCheckOut">

            <div id="bookingError" class="hidden mt-4 p-3 bg-[#f04141]/10 border border-[#f04141]/30 rounded-xl text-sm text-[#ff8a8a]"></div>

            <div class="flex gap-3 pt-5">
                <button onclick="proceedToBooking()" class="btn-accent flex-1 py-3">{{ __('messages.continue') }}</button>
                <button onclick="closeBookingModal()" class="btn-dark px-6 py-3">{{ __('messages.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
@endauth

<script>
const APP_LOCALE = '{{ app()->getLocale() }}';
const T = {
    choose_checkin: @json(__('messages.choose_checkin')),
    choose_checkout: @json(__('messages.choose_checkout')),
    err_select_dates: @json(__('messages.err_select_dates')),
    err_checkout_after: @json(__('messages.err_checkout_after')),
    err_no_room: @json(__('messages.err_no_room')),
    room_busy_until: @json(__('messages.room_busy_until')),
    room_unavailable: @json(__('messages.room_unavailable')),
    room_taken: @json(__('messages.room_taken')),
};

// --- Галерея (лайтбокс) ---
const GALLERY = @json($gallery ?? []);
let glbIndex = 0;

function openGallery(i) {
    if (!GALLERY.length) return;
    glbIndex = i;
    renderGlb();
    document.getElementById('galleryLightbox').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeGallery() {
    document.getElementById('galleryLightbox').classList.add('hidden');
    document.body.style.overflow = '';
}
function galleryPrev() { glbIndex = (glbIndex - 1 + GALLERY.length) % GALLERY.length; renderGlb(); }
function galleryNext() { glbIndex = (glbIndex + 1) % GALLERY.length; renderGlb(); }
function renderGlb() {
    document.getElementById('glbImg').src = GALLERY[glbIndex];
    document.getElementById('glbCounter').textContent = (glbIndex + 1) + ' / ' + GALLERY.length;
}
document.addEventListener('keydown', function (e) {
    const lb = document.getElementById('galleryLightbox');
    if (!lb || lb.classList.contains('hidden')) return;
    if (e.key === 'Escape') closeGallery();
    else if (e.key === 'ArrowLeft') galleryPrev();
    else if (e.key === 'ArrowRight') galleryNext();
});
document.getElementById('galleryLightbox')?.addEventListener('click', function (e) {
    if (e.target === this) closeGallery();
});

// Контекст текущего отеля для ИИ-помощника
window.AI_PAGE_CONTEXT = {
    hotel: @json($hotel->name),
    city: @json($hotel->city),
};

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

// Даты, выбранные при поиске (приходят в query-параметрах страницы)
const BK_SEARCH_IN = @json(request('check_in'));
const BK_SEARCH_OUT = @json(request('check_out'));

function goToBooking(roomId, checkIn, checkOut) {
    const url = new URL('{{ route("bookings.create") }}', window.location.origin);
    url.searchParams.set('room_id', roomId);
    url.searchParams.set('check_in', checkIn);
    url.searchParams.set('check_out', checkOut);
    window.location.href = url.toString();
}

// Проверяем занятость без перезагрузки: если занято — тост, иначе оформление
function checkAndBook(roomId, checkIn, checkOut) {
    fetch(`/room/${roomId}/availability?check_in=${encodeURIComponent(checkIn)}&check_out=${encodeURIComponent(checkOut)}`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.available) {
            goToBooking(roomId, checkIn, checkOut);
        } else if (typeof showToast === 'function') {
            let msg;
            if (data.reason === 'disabled') {
                msg = T.room_unavailable;
            } else if (data.busy_until) {
                msg = T.room_busy_until.replace(':date', data.busy_until);
            } else {
                msg = T.room_taken;
            }
            showToast(msg, 'error');
        }
    })
    .catch(() => goToBooking(roomId, checkIn, checkOut));
}

function openBookingModal(roomId) {
    selectedRoomId = roomId;
    // Если даты уже выбраны при поиске — не спрашиваем повторно, но проверяем занятость
    if (BK_SEARCH_IN && BK_SEARCH_OUT) {
        checkAndBook(roomId, BK_SEARCH_IN, BK_SEARCH_OUT);
        return;
    }
    const modal = document.getElementById('bookingDateModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        initBkCalendar();
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
    bkCheckIn = null;
    bkCheckOut = null;
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

    if (!checkIn || !checkOut) { showBookingError(T.err_select_dates); return; }
    if (new Date(checkOut) <= new Date(checkIn)) { showBookingError(T.err_checkout_after); return; }
    if (!selectedRoomId) { showBookingError(T.err_no_room); return; }

    hideBookingError();
    checkAndBook(selectedRoomId, checkIn, checkOut);
}

document.getElementById('bookingDateModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeBookingModal();
});

/* ---------- Календарь в модалке бронирования ---------- */
const BK_MONTHS = ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'];
let bkView = new Date();
let bkCheckIn = null;
let bkCheckOut = null;

function bkFmt(d) {
    return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
}
function bkStrip(d) { return new Date(d.getFullYear(), d.getMonth(), d.getDate()); }

function initBkCalendar() {
    bkCheckIn = null;
    bkCheckOut = null;
    document.getElementById('bookingCheckIn').value = '';
    document.getElementById('bookingCheckOut').value = '';
    bkView = new Date(new Date().getFullYear(), new Date().getMonth(), 1);
    bkRender();
}

function bkPrevMonth() {
    const today = bkStrip(new Date());
    const firstThis = new Date(today.getFullYear(), today.getMonth(), 1);
    const cand = new Date(bkView.getFullYear(), bkView.getMonth() - 1, 1);
    if (cand >= firstThis) { bkView = cand; bkRender(); }
}
function bkNextMonth() {
    bkView = new Date(bkView.getFullYear(), bkView.getMonth() + 1, 1);
    bkRender();
}

function bkOnDay(ds) {
    const p = ds.split('-');
    const d = new Date(+p[0], +p[1] - 1, +p[2]);
    if (!bkCheckIn || (bkCheckIn && bkCheckOut)) {
        bkCheckIn = d; bkCheckOut = null;
    } else if (d > bkCheckIn) {
        bkCheckOut = d;
    } else {
        bkCheckIn = d; bkCheckOut = null;
    }
    document.getElementById('bookingCheckIn').value = bkCheckIn ? bkFmt(bkCheckIn) : '';
    document.getElementById('bookingCheckOut').value = bkCheckOut ? bkFmt(bkCheckOut) : '';
    hideBookingError();
    bkRender();
}

function bkRender() {
    const label = document.getElementById('bkCalMonthLabel');
    const grid = document.getElementById('bkCalDays');
    const hint = document.getElementById('bkCalHint');
    const prevBtn = document.getElementById('bkCalPrev');
    if (!grid) return;

    const ml = bkView.toLocaleDateString(APP_LOCALE, { month: 'long', year: 'numeric' });
    label.textContent = ml.charAt(0).toUpperCase() + ml.slice(1);

    const today = bkStrip(new Date());
    const firstThis = new Date(today.getFullYear(), today.getMonth(), 1);
    if (prevBtn) prevBtn.disabled = (new Date(bkView.getFullYear(), bkView.getMonth(), 1) <= firstThis);

    const year = bkView.getFullYear();
    const month = bkView.getMonth();
    let offset = new Date(year, month, 1).getDay() - 1;
    if (offset < 0) offset = 6;
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    let html = '';
    for (let i = 0; i < offset; i++) html += '<span class="cal-empty"></span>';
    for (let day = 1; day <= daysInMonth; day++) {
        const d = new Date(year, month, day);
        const ds = bkFmt(d);
        const dow = d.getDay();
        const isWeekend = (dow === 0 || dow === 6);
        const isPast = d < today;

        let cls = 'cal-day';
        if (isPast) {
            cls += ' cal-disabled';
        } else {
            if (isWeekend) cls += ' cal-weekend';
            const isStart = bkCheckIn && d.getTime() === bkCheckIn.getTime();
            const isEnd = bkCheckOut && d.getTime() === bkCheckOut.getTime();
            const inRange = bkCheckIn && bkCheckOut && d > bkCheckIn && d < bkCheckOut;
            if (isStart || isEnd) cls += ' cal-selected';
            else if (inRange) cls += ' cal-inrange';
        }

        if (isPast) html += `<span class="${cls}">${day}</span>`;
        else html += `<button type="button" class="${cls}" onclick="bkOnDay('${ds}')">${day}</button>`;
    }
    grid.innerHTML = html;

    if (hint) {
        if (bkCheckIn && bkCheckOut) {
            const opt = { day: 'numeric', month: 'short' };
            hint.textContent = bkCheckIn.toLocaleDateString(APP_LOCALE, opt) + ' – ' + bkCheckOut.toLocaleDateString(APP_LOCALE, opt);
        } else if (bkCheckIn) {
            hint.textContent = T.choose_checkout;
        } else {
            hint.textContent = T.choose_checkin;
        }
    }
}
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

/* Лайтбокс галереи */
.glb {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.92);
    z-index: 70;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}
.glb.hidden { display: none !important; }
.glb:not(.hidden) { display: flex; }
.glb-img {
    max-width: 92vw;
    max-height: 86vh;
    object-fit: contain;
    border-radius: 12px;
    user-select: none;
}
.glb-close {
    position: absolute;
    top: 1.25rem;
    right: 1.5rem;
    color: #fff;
    background: rgba(255, 255, 255, 0.12);
    border-radius: 9999px;
    padding: .6rem;
    transition: background-color .15s ease;
}
.glb-close:hover { background: rgba(255, 255, 255, 0.22); }
.glb-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    color: #fff;
    background: rgba(255, 255, 255, 0.12);
    border-radius: 9999px;
    padding: .75rem;
    transition: background-color .15s ease;
}
.glb-nav:hover { background: rgba(255, 255, 255, 0.22); }
.glb-prev { left: 1.5rem; }
.glb-next { right: 1.5rem; }
.glb-counter {
    position: absolute;
    bottom: 1.5rem;
    left: 50%;
    transform: translateX(-50%);
    color: #fff;
    background: rgba(0, 0, 0, 0.5);
    padding: .35rem .9rem;
    border-radius: 9999px;
    font-size: .875rem;
}
</style>
@endsection
