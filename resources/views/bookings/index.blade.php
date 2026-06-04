@extends('layouts.app')

@section('title', 'Бронирования - Kazakh Hotels')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Header card with tabs --}}
    <div class="otl-surface px-6 py-5 mb-6">
        <h1 class="text-2xl font-extrabold text-white mb-4">Бронирования</h1>
        <div class="flex gap-2 overflow-x-auto">
            <a href="{{ route('bookings.index', ['tab' => 'active']) }}"
               class="px-5 py-2 rounded-full text-sm font-medium transition whitespace-nowrap {{ $tab === 'active' ? 'bg-[#8ee30f] text-[#0a0a0a]' : 'bg-[#2a2b2c] text-gray-300 hover:bg-[#343536]' }}">
                Активные
            </a>
            <a href="{{ route('bookings.index', ['tab' => 'past']) }}"
               class="px-5 py-2 rounded-full text-sm font-medium transition whitespace-nowrap {{ $tab === 'past' ? 'bg-[#8ee30f] text-[#0a0a0a]' : 'bg-[#2a2b2c] text-gray-300 hover:bg-[#343536]' }}">
                Прошлые
            </a>
            <a href="{{ route('bookings.index', ['tab' => 'cancelled']) }}"
               class="px-5 py-2 rounded-full text-sm font-medium transition whitespace-nowrap {{ $tab === 'cancelled' ? 'bg-[#8ee30f] text-[#0a0a0a]' : 'bg-[#2a2b2c] text-gray-300 hover:bg-[#343536]' }}">
                Отменённые
            </a>
        </div>
    </div>

    @if($bookings->isEmpty())
        <div class="otl-surface py-16 px-6 text-center">
            <div class="w-16 h-16 bg-[#141516] rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-8 h-8 text-[#8ee30f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h2 class="text-white text-xl font-bold mb-2">Здесь пока пусто</h2>
            <p class="text-[#7e8488] mb-6">
                @if($tab === 'active')
                    Пора выбрать идеальный отель и отправиться в новое путешествие
                @elseif($tab === 'past')
                    У вас пока нет завершённых бронирований
                @else
                    У вас нет отменённых бронирований
                @endif
            </p>
            <a href="{{ route('hotels.index') }}" class="btn-accent px-6 py-3">Выбрать отель</a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <div class="otl-surface p-6">
                    <div class="flex items-start justify-between gap-4 mb-5">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-white text-lg font-bold mb-1 truncate">{{ $booking->room->hotel->name }}</h3>
                            <div class="text-gray-300 mb-2">{{ $booking->room->name }}</div>
                            <div class="flex items-center gap-2 text-sm text-[#7e8488]">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="truncate">{{ $booking->room->hotel->city }}</span>
                            </div>
                        </div>
                        <span class="px-3 py-1.5 rounded-full text-sm flex-shrink-0 font-medium
                            {{ $booking->status === 'confirmed' ? 'bg-[#8ee30f]/15 text-[#8ee30f]' :
                               ($booking->status === 'cancelled' ? 'bg-[#f04141]/15 text-[#ff8a8a]' :
                                'bg-yellow-400/15 text-yellow-300') }}">
                            @if($booking->status === 'confirmed') Подтверждено
                            @elseif($booking->status === 'cancelled') Отменено
                            @else Ожидает @endif
                        </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-[#141516] rounded-2xl mb-4">
                        <div>
                            <div class="text-xs text-[#7e8488] mb-1">Заезд</div>
                            <div class="text-sm text-white font-medium">{{ $booking->check_in->locale('ru')->translatedFormat('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-[#7e8488] mb-1">Выезд</div>
                            <div class="text-sm text-white font-medium">{{ $booking->check_out->locale('ru')->translatedFormat('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-[#7e8488] mb-1">Гостей</div>
                            <div class="text-sm text-white font-medium">{{ $booking->guests }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-[#7e8488] mb-1">Сумма</div>
                            <div class="text-sm text-[#8ee30f] font-bold">{{ number_format($booking->total_price, 0, '.', ' ') }} ₸</div>
                        </div>
                    </div>

                    @if($booking->status !== 'cancelled')
                        <div class="flex gap-3">
                            <a href="{{ route('bookings.show', $booking) }}" class="btn-dark flex-1 py-3">Подробнее</a>
                            @if($booking->status === 'pending')
                                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="flex-1" onsubmit="return confirm('Вы уверены, что хотите отменить бронирование?');">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 border border-[#f04141]/40 text-[#ff8a8a] rounded-full hover:bg-[#f04141]/10 transition font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <span>Отменить</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        @if($bookings->hasPages())
            <div class="mt-8">{{ $bookings->links() }}</div>
        @endif
    @endif
</div>
@endsection
