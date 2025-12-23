@extends('layouts.app')

@section('title', 'Бронирование номера - Kazakh Hotels')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <div class="mb-6">
            <h1 class="text-gray-900 mb-2 text-3xl font-bold">Оформление бронирования</h1>
            <p class="text-gray-600">Проверьте детали и завершите бронирование</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Hotel & Room Info --}}
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-gray-900 text-xl font-bold">Детали бронирования</h2>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        {{-- Hotel Info --}}
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-[#38b000]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-[#38b000]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm text-gray-500 mb-1">Отель</div>
                                <div class="text-gray-900 font-medium">{{ $room->hotel->name }}</div>
                                <div class="text-sm text-gray-600 mt-1">{{ $room->hotel->city }}, {{ $room->hotel->address }}</div>
                            </div>
                        </div>

                        {{-- Room Info --}}
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-[#38b000]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-[#38b000]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm text-gray-500 mb-1">Номер</div>
                                <div class="text-gray-900 font-medium">{{ $room->name }}</div>
                                <div class="text-sm text-gray-600 mt-1">Вместимость: до {{ $room->capacity }} {{ $room->capacity == 1 ? 'гостя' : 'гостей' }}</div>
                            </div>
                        </div>

                        {{-- Dates --}}
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-[#38b000]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-[#38b000]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm text-gray-500 mb-2">Даты проживания</div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-xs text-gray-500 mb-1">Заезд</div>
                                        <div class="text-gray-900 font-medium" id="check-in-display">
                                            @if($checkIn)
                                                {{ \Carbon\Carbon::parse($checkIn)->format('d F Y') }}
                                            @else
                                                Не выбрано
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500 mb-1">Выезд</div>
                                        <div class="text-gray-900 font-medium" id="check-out-display">
                                            @if($checkOut)
                                                {{ \Carbon\Carbon::parse($checkOut)->format('d F Y') }}
                                            @else
                                                Не выбрано
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 text-sm text-gray-600">
                                    Всего ночей: <span id="nights-count">{{ $nights }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Guest Details --}}
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-gray-900 text-xl font-bold">Данные гостей</h2>
                    </div>
                    
                    <div class="p-6 space-y-5">
                        <form id="booking-form" action="{{ route('bookings.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="room_id" value="{{ $room->id }}">
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm text-gray-700 mb-2">
                                        Количество гостей
                                    </label>
                                    <input
                                        type="number"
                                        name="guests"
                                        required
                                        min="1"
                                        max="{{ $room->capacity }}"
                                        value="{{ old('guests', $guests) }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#38b000] focus:border-transparent"
                                    />
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-700 mb-2">
                                        Особые пожелания <span class="text-gray-400">(опционально)</span>
                                    </label>
                                    <textarea
                                        name="special_requests"
                                        placeholder="Дополнительная подушка, ранний заезд, поздний выезд и т.д."
                                        rows="4"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#38b000] focus:border-transparent resize-none"
                                    >{{ old('special_requests') }}</textarea>
                                </div>

                                {{-- Hidden date inputs --}}
                                <input type="hidden" name="check_in" id="check_in_input" value="{{ old('check_in', $checkIn) }}">
                                <input type="hidden" name="check_out" id="check_out_input" value="{{ old('check_out', $checkOut) }}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Sidebar - Booking Summary --}}
            <div class="lg:col-span-1">
                <div class="sticky top-24">
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        <div class="p-6 bg-gradient-to-br from-[#38b000] to-[#2d8c00]">
                            <h2 class="text-white text-xl font-bold mb-1">Итоговая стоимость</h2>
                            <div class="text-3xl font-bold text-white" id="total-price-display">
                                {{ number_format($totalPrice, 0) }} ₸
                            </div>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <div class="space-y-3">
                                <div class="flex justify-between text-gray-600">
                                    <span>Цена за ночь</span>
                                    <span>{{ number_format($room->price_per_night, 0) }} ₸</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Количество ночей</span>
                                    <span>× <span id="nights-display">{{ $nights }}</span></span>
                                </div>
                                
                                <div class="border-t border-gray-200 pt-3">
                                    <div class="flex justify-between text-gray-900 font-semibold">
                                        <span>Итого к оплате</span>
                                        <span id="total-price-sidebar">{{ number_format($totalPrice, 0) }} ₸</span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" form="booking-form" class="w-full py-4 bg-[#38b000] text-white rounded-xl hover:bg-[#2d8c00] transition font-semibold shadow-lg hover:shadow-xl">
                                Забронировать
                            </button>

                            <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                                <div class="flex items-start gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-[#38b000] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Бесплатная отмена за 24 часа</span>
                                </div>
                                <div class="flex items-start gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-[#38b000] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Мгновенное подтверждение</span>
                                </div>
                                <div class="flex items-start gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-[#38b000] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Безопасная оплата</span>
                                </div>
                            </div>

                            <p class="text-xs text-gray-500 text-center">
                                Нажимая кнопку "Забронировать", вы соглашаетесь с условиями бронирования и политикой конфиденциальности
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const checkInInput = document.getElementById('check_in_input');
    const checkOutInput = document.getElementById('check_out_input');
    const checkInDisplay = document.getElementById('check-in-display');
    const checkOutDisplay = document.getElementById('check-out-display');
    const nightsCount = document.getElementById('nights-count');
    const nightsDisplay = document.getElementById('nights-display');
    const totalPriceDisplay = document.getElementById('total-price-display');
    const totalPriceSidebar = document.getElementById('total-price-sidebar');
    const pricePerNight = {{ $room->price_per_night }};
    
    // If dates are not set, redirect to hotel page to select dates
    @if(!$checkIn || !$checkOut)
        setTimeout(function() {
            window.location.href = '{{ route("hotels.show", $room->hotel->id) }}?room_id={{ $room->id }}';
        }, 100);
    @endif

    function calculatePrice() {
        const checkIn = checkInInput.value;
        const checkOut = checkOutInput.value;
        
        if (checkIn && checkOut) {
            const checkInDate = new Date(checkIn);
            const checkOutDate = new Date(checkOut);
            const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
            
            if (nights > 0) {
                const total = nights * pricePerNight;
                const formattedTotal = total.toLocaleString('ru-RU');
                
                totalPriceDisplay.textContent = formattedTotal + ' ₸';
                totalPriceSidebar.textContent = formattedTotal + ' ₸';
                nightsCount.textContent = nights;
                nightsDisplay.textContent = nights;
                
                // Update displays
                checkInDisplay.textContent = checkInDate.toLocaleDateString('ru-RU', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                checkOutDisplay.textContent = checkOutDate.toLocaleDateString('ru-RU', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
            }
        }
    }
    
    // Initial calculation
    calculatePrice();
});
</script>
@endsection
