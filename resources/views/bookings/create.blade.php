@extends('layouts.app')

@section('title', 'Бронирование номера - Kazakh Hotels')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold mb-8 text-gray-900">Бронирование номера</h1>

        @if($room)
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-2xl font-semibold mb-2 text-gray-900">{{ $room->hotel->name }}</h2>
                        <p class="text-gray-600 mb-2">{{ $room->name }}</p>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span>Вместимость: {{ $room->capacity }} {{ $room->capacity == 1 ? 'гость' : 'гостей' }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-gray-900">{{ number_format($room->price_per_night, 0) }} ₸</div>
                        <div class="text-sm text-gray-500">за ночь</div>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('bookings.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm p-6 md:p-8">
        @csrf

            @if($room)
                <input type="hidden" name="room_id" value="{{ $room->id }}">
            @else
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2 text-gray-700">Выберите номер</label>
                    <select name="room_id" required class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-[#0066cc] focus:border-[#0066cc] outline-none">
                        <option value="">Выберите номер...</option>
                        <!-- Rooms will be loaded here -->
                    </select>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700">Дата заезда</label>
                    <input type="date" name="check_in" required 
                           min="{{ date('Y-m-d') }}"
                           value="{{ old('check_in') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-[#0066cc] focus:border-[#0066cc] outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700">Дата выезда</label>
                    <input type="date" name="check_out" required 
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           value="{{ old('check_out') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-[#0066cc] focus:border-[#0066cc] outline-none">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-2 text-gray-700">Количество гостей</label>
                <input type="number" name="guests" required min="1" 
                       value="{{ old('guests', $room->capacity ?? 1) }}"
                       max="{{ $room->capacity ?? 10 }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-[#0066cc] focus:border-[#0066cc] outline-none">
            </div>

            <div id="price-calculation" class="mb-6 p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-100">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-lg font-semibold text-gray-900">Итого:</span>
                    <span class="text-3xl font-bold text-[#0066cc]" id="total-price">0</span>
                </div>
                <p class="text-sm text-gray-600" id="nights-info">Выберите даты для расчета стоимости</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <button type="submit" class="flex-1 px-6 py-4 bg-[#0066cc] text-white rounded-xl hover:bg-[#0052a3] transition font-semibold shadow-lg hover:shadow-xl">
                    Подтвердить бронирование
                </button>
                <a href="{{ url()->previous() }}" class="px-6 py-4 bg-gray-100 text-gray-800 rounded-xl hover:bg-gray-200 transition font-semibold text-center">
                    Отмена
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkIn = document.querySelector('input[name="check_in"]');
    const checkOut = document.querySelector('input[name="check_out"]');
    const pricePerNight = {{ $room->price_per_night ?? 0 }};
    
    function calculatePrice() {
        if (checkIn.value && checkOut.value) {
            const checkInDate = new Date(checkIn.value);
            const checkOutDate = new Date(checkOut.value);
            const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
            
            if (nights > 0) {
                const total = nights * pricePerNight;
                document.getElementById('total-price').textContent = total.toLocaleString('ru-RU');
                const nightsText = nights === 1 ? 'ночь' : (nights < 5 ? 'ночи' : 'ночей');
                document.getElementById('nights-info').textContent = `${nights} ${nightsText} × ${pricePerNight.toLocaleString('ru-RU')} ₸`;
            } else {
                document.getElementById('total-price').textContent = '0';
                document.getElementById('nights-info').textContent = 'Дата выезда должна быть позже даты заезда';
            }
        } else {
            document.getElementById('total-price').textContent = '0';
            document.getElementById('nights-info').textContent = 'Выберите даты для расчета стоимости';
        }
    }
    
    checkIn.addEventListener('change', function() {
        if (this.value) {
            const minDate = new Date(this.value);
            minDate.setDate(minDate.getDate() + 1);
            checkOut.min = minDate.toISOString().split('T')[0];
            if (checkOut.value && checkOut.value <= this.value) {
                checkOut.value = '';
            }
        }
        calculatePrice();
    });
    
    checkOut.addEventListener('change', calculatePrice);
});
</script>
@endsection


