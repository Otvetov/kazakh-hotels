@extends('layouts.app')

@section('title', 'Бронирование - Kazakh Hotels')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="otl-surface p-10 md:p-12 max-w-md w-full text-center">
        <div class="w-20 h-20 bg-[#8ee30f]/10 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-[#8ee30f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h2 class="text-white text-2xl font-extrabold mb-3">Бронирование успешно!</h2>
        <p class="text-[#7e8488] mb-8">
            Ваше бронирование принято и ожидает подтверждения. Детали доступны в разделе «Бронирования».
        </p>

        <div class="bg-[#141516] rounded-2xl p-5 mb-6 text-left">
            <div class="space-y-2.5 text-sm">
                <div class="flex justify-between gap-4">
                    <span class="text-[#7e8488]">Отель</span>
                    <span class="text-white font-medium text-right">{{ $booking->room->hotel->name }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-[#7e8488]">Номер</span>
                    <span class="text-white font-medium text-right">{{ $booking->room->name }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-[#7e8488]">Даты</span>
                    <span class="text-white font-medium text-right">{{ $booking->check_in->format('d.m.Y') }} – {{ $booking->check_out->format('d.m.Y') }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-[#7e8488]">Гостей</span>
                    <span class="text-white font-medium">{{ $booking->guests }}</span>
                </div>
                <div class="flex justify-between gap-4 pt-3 border-t border-white/10">
                    <span class="text-white font-semibold">Итого</span>
                    <span class="text-[#8ee30f] font-bold text-lg">{{ number_format($booking->total_price, 0, '.', ' ') }} ₸</span>
                </div>
            </div>
        </div>

        <div class="space-y-3">
            <a href="{{ route('bookings.index') }}" class="btn-accent w-full py-3">Мои бронирования</a>
            <a href="{{ route('home') }}" class="btn-dark w-full py-3">На главную</a>
        </div>
    </div>
</div>
@endsection
