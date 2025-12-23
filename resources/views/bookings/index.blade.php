@extends('layouts.app')

@section('title', 'Бронирования - Kazakh Hotels')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-gray-900 mb-2 text-3xl font-bold">Бронирования</h1>
            <p class="text-gray-600">Управление вашими бронированиями</p>
        </div>

        {{-- Tabs --}}
        <div class="flex gap-2 mb-8 overflow-x-auto">
            <a href="{{ route('bookings.index', ['tab' => 'active']) }}" 
               class="px-6 py-3 rounded-xl transition-all whitespace-nowrap {{ $tab === 'active' ? 'bg-gray-900 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                Активные
            </a>
            <a href="{{ route('bookings.index', ['tab' => 'past']) }}" 
               class="px-6 py-3 rounded-xl transition-all whitespace-nowrap {{ $tab === 'past' ? 'bg-gray-900 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                Прошлые
            </a>
            <a href="{{ route('bookings.index', ['tab' => 'cancelled']) }}" 
               class="px-6 py-3 rounded-xl transition-all whitespace-nowrap {{ $tab === 'cancelled' ? 'bg-gray-900 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                Отменённые
            </a>
        </div>

        {{-- Content --}}
        @if($bookings->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h2 class="text-gray-900 mb-2 text-xl font-semibold">Нет бронирований</h2>
                <p class="text-gray-500 mb-6">
                    @if($tab === 'active')
                        У вас пока нет активных бронирований
                    @elseif($tab === 'past')
                        У вас пока нет завершённых бронирований
                    @else
                        У вас нет отменённых бронирований
                    @endif
                </p>
                <a href="{{ route('hotels.index') }}" 
                   class="inline-block px-8 py-3 bg-[#38b000] text-white rounded-xl hover:bg-[#2d8c00] transition-colors">
                    Найти отель
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($bookings as $booking)
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between gap-4 mb-5">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-gray-900 mb-2 truncate text-lg font-semibold">
                                        {{ $booking->room->hotel->name }}
                                    </h3>
                                    <div class="text-gray-700 mb-2">
                                        {{ $booking->room->name }}
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-gray-500">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="truncate">{{ $booking->room->hotel->city }}</span>
                                    </div>
                                </div>
                                <span class="px-4 py-2 rounded-full text-sm flex-shrink-0 font-medium
                                    {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                       ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                        'bg-yellow-100 text-yellow-800') }}">
                                    @if($booking->status === 'confirmed')
                                        Подтверждено
                                    @elseif($booking->status === 'cancelled')
                                        Отменено
                                    @else
                                        Ожидает
                                    @endif
                                </span>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-gray-50 rounded-xl mb-4">
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Заезд</div>
                                    <div class="text-sm text-gray-900 font-medium">
                                        {{ $booking->check_in->locale('ru')->translatedFormat('d M Y') }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Выезд</div>
                                    <div class="text-sm text-gray-900 font-medium">
                                        {{ $booking->check_out->locale('ru')->translatedFormat('d M Y') }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Гостей</div>
                                    <div class="text-sm text-gray-900 font-medium">{{ $booking->guests }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Сумма</div>
                                    <div class="text-sm text-gray-900 font-medium">
                                        {{ number_format($booking->total_price, 0) }} ₸
                                    </div>
                                </div>
                            </div>

                            @if($booking->status !== 'cancelled')
                                <div class="flex gap-3">
                                    <a href="{{ route('hotels.show', $booking->room->hotel->id) }}" 
                                       class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors text-center font-medium">
                                        Подробнее
                                    </a>
                                    @if($booking->status === 'pending')
                                        <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="flex-1" onsubmit="return confirm('Вы уверены, что хотите отменить бронирование?');">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full flex items-center justify-center gap-2 px-6 py-3 border-2 border-red-200 text-red-600 rounded-xl hover:bg-red-50 transition-colors font-medium">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>Отменить</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($bookings->hasPages())
                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
