@extends('layouts.app')

@section('title', 'Бронирование номера - Kazakh Hotels')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-white text-2xl md:text-3xl font-extrabold mb-1">{{ __('messages.checkout_title') }}</h1>
        <p class="text-[#7e8488]">{{ __('messages.checkout_subtitle') }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="otl-surface overflow-hidden">
                <div class="p-6 border-b border-white/10">
                    <h2 class="text-white text-xl font-bold">{{ __('messages.booking_details') }}</h2>
                </div>
                <div class="p-6 space-y-6">
                    {{-- Hotel --}}
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-[#8ee30f]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-[#8ee30f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm text-[#7e8488] mb-1">{{ __('messages.hotel') }}</div>
                            <div class="text-white font-medium">{{ $room->hotel->name }}</div>
                            <div class="text-sm text-[#7e8488] mt-1">{{ $room->hotel->city }}, {{ $room->hotel->address }}</div>
                        </div>
                    </div>

                    {{-- Room --}}
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-[#8ee30f]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-[#8ee30f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm text-[#7e8488] mb-1">{{ __('messages.room') }}</div>
                            <div class="text-white font-medium">{{ $room->name }}</div>
                            <div class="text-sm text-[#7e8488] mt-1">{{ __('messages.capacity_up_to', ['count' => $room->capacity]) }}</div>
                        </div>
                    </div>

                    {{-- Dates --}}
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-[#8ee30f]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-[#8ee30f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm text-[#7e8488] mb-2">{{ __('messages.living_dates') }}</div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="text-xs text-[#7e8488] mb-1">{{ __('messages.checkin') }}</div>
                                    <div class="text-white font-medium" id="check-in-display">
                                        {{ $checkIn ? \Carbon\Carbon::parse($checkIn)->format('d.m.Y') : __('messages.not_selected') }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs text-[#7e8488] mb-1">{{ __('messages.checkout') }}</div>
                                    <div class="text-white font-medium" id="check-out-display">
                                        {{ $checkOut ? \Carbon\Carbon::parse($checkOut)->format('d.m.Y') : __('messages.not_selected') }}
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 text-sm text-[#7e8488]">{{ __('messages.total_nights') }}<span id="nights-count">{{ $nights }}</span></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Guest form --}}
            <div class="otl-surface overflow-hidden">
                <div class="p-6 border-b border-white/10">
                    <h2 class="text-white text-xl font-bold">{{ __('messages.guest_data') }}</h2>
                </div>
                <div class="p-6">
                    <form id="booking-form" action="{{ route('bookings.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm text-[#7e8488] mb-2">{{ __('messages.guests_count_field') }}</label>
                                <input type="number" name="guests" required min="1" max="{{ $room->capacity }}"
                                       value="{{ old('guests', $guests) }}" class="field-input">
                            </div>
                            <div>
                                <label class="block text-sm text-[#7e8488] mb-2">{{ __('messages.special_requests') }} <span class="text-[#7e8488]">{{ __('messages.optional') }}</span></label>
                                <textarea name="special_requests" rows="4"
                                          placeholder="{{ __('messages.special_requests_ph') }}"
                                          class="field-input resize-none">{{ old('special_requests') }}</textarea>
                            </div>
                            <input type="hidden" name="check_in" id="check_in_input" value="{{ old('check_in', $checkIn) }}">
                            <input type="hidden" name="check_out" id="check_out_input" value="{{ old('check_out', $checkOut) }}">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1">
            <div class="sticky top-24">
                <div class="otl-surface overflow-hidden">
                    <div class="p-6 bg-gradient-to-br from-[#8ee30f] to-[#76c406]">
                        <h2 class="text-[#0a0a0a] text-lg font-bold mb-1">{{ __('messages.total_cost') }}</h2>
                        <div class="text-3xl font-extrabold text-[#0a0a0a]" id="total-price-display">{{ number_format($totalPrice, 0, '.', ' ') }} ₸</div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="space-y-3">
                            <div class="flex justify-between text-[#7e8488]">
                                <span>{{ __('messages.price_per_night_label') }}</span>
                                <span>{{ number_format($room->price_per_night, 0, '.', ' ') }} ₸</span>
                            </div>
                            <div class="flex justify-between text-[#7e8488]">
                                <span>{{ __('messages.nights_label') }}</span>
                                <span>× <span id="nights-display">{{ $nights }}</span></span>
                            </div>
                            <div class="border-t border-white/10 pt-3">
                                <div class="flex justify-between text-white font-semibold">
                                    <span>{{ __('messages.total_to_pay') }}</span>
                                    <span id="total-price-sidebar">{{ number_format($totalPrice, 0, '.', ' ') }} ₸</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" form="booking-form" id="booking-submit-btn" class="btn-accent w-full py-4 disabled:opacity-40 disabled:cursor-not-allowed">
                            <span id="booking-btn-text">{{ __('messages.book') }}</span>
                        </button>

                        <div class="bg-[#141516] rounded-2xl p-4 space-y-2">
                            @foreach([__('messages.perk_1'), __('messages.perk_2'), __('messages.perk_3')] as $perk)
                                <div class="flex items-start gap-2 text-sm text-gray-300">
                                    <svg class="w-4 h-4 text-[#8ee30f] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $perk }}</span>
                                </div>
                            @endforeach
                        </div>

                        <p class="text-xs text-[#7e8488] text-center">
                            {{ __('messages.agree_terms') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkInInput = document.getElementById('check_in_input');
    const checkOutInput = document.getElementById('check_out_input');
    const checkInDisplay = document.getElementById('check-in-display');
    const checkOutDisplay = document.getElementById('check-out-display');
    const nightsCount = document.getElementById('nights-count');
    const nightsDisplay = document.getElementById('nights-display');
    const totalPriceDisplay = document.getElementById('total-price-display');
    const totalPriceSidebar = document.getElementById('total-price-sidebar');
    const pricePerNight = {{ $room->price_per_night }};

    function calculatePrice() {
        const checkIn = checkInInput.value;
        const checkOut = checkOutInput.value;
        if (checkIn && checkOut) {
            const checkInDate = new Date(checkIn);
            const checkOutDate = new Date(checkOut);
            const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
            if (nights > 0) {
                const total = nights * pricePerNight;
                const formattedTotal = total.toLocaleString('ru-RU') + ' ₸';
                totalPriceDisplay.textContent = formattedTotal;
                totalPriceSidebar.textContent = formattedTotal;
                nightsCount.textContent = nights;
                nightsDisplay.textContent = nights;
                const opt = { day: 'numeric', month: 'long', year: 'numeric' };
                checkInDisplay.textContent = checkInDate.toLocaleDateString('{{ app()->getLocale() }}', opt);
                checkOutDisplay.textContent = checkOutDate.toLocaleDateString('{{ app()->getLocale() }}', opt);
            }
        }
    }

    calculatePrice();

    const form = document.getElementById('booking-form');
    const submitBtn = document.getElementById('booking-submit-btn');
    const btnText = document.getElementById('booking-btn-text');

    if (form) {
        form.addEventListener('submit', function(e) {
            if (!checkInInput.value || !checkOutInput.value) {
                e.preventDefault();
                alert(@json(__('messages.err_select_dates')));
                return false;
            }
            submitBtn.disabled = true;
            btnText.textContent = 'Оформление...';
        });
    }
});
</script>
@endsection
