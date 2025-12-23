@extends('layouts.app')

@section('title', 'Бронирование успешно - Kazakh Hotels')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <div class="bg-white rounded-2xl shadow-xl p-12 max-w-md w-full text-center">
        <div class="w-20 h-20 bg-[#38b000]/10 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-[#38b000]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h2 class="text-gray-900 text-2xl font-bold mb-3">Бронирование успешно!</h2>
        <p class="text-gray-600 mb-8">
            Ваше бронирование принято и ожидает подтверждения. Вы можете просмотреть детали в личном кабинете.
        </p>
        
        <div class="bg-gray-50 rounded-xl p-4 mb-6 text-left">
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Отель:</span>
                    <span class="text-gray-900 font-medium">{{ $booking->room->hotel->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Номер:</span>
                    <span class="text-gray-900 font-medium">{{ $booking->room->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Даты:</span>
                    <span class="text-gray-900 font-medium">
                        {{ $booking->check_in->format('d.m.Y') }} - {{ $booking->check_out->format('d.m.Y') }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Гостей:</span>
                    <span class="text-gray-900 font-medium">{{ $booking->guests }}</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-gray-200">
                    <span class="text-gray-900 font-semibold">Итого:</span>
                    <span class="text-[#38b000] font-bold text-lg">{{ number_format($booking->total_price, 0) }} ₸</span>
                </div>
            </div>
        </div>
        
        <div class="space-y-3">
            <a
                href="{{ route('bookings.index') }}"
                class="block w-full py-3 bg-[#38b000] text-white rounded-xl hover:bg-[#2d8c00] transition-colors font-semibold"
            >
                Перейти в профиль
            </a>
            <a
                href="{{ route('home') }}"
                class="block w-full py-3 text-gray-700 border-2 border-gray-300 rounded-xl hover:border-[#38b000] hover:text-[#38b000] transition-colors font-semibold"
            >
                На главную
            </a>
        </div>
    </div>
</div>
@endsection
