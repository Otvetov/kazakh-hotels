@extends('layouts.app')

@section('title', 'Панель администратора - Kazakh Hotels')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-extrabold text-white mb-6">Панель администратора</h1>

    {{-- Statistics --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        <div class="otl-surface p-6">
            <h3 class="text-sm font-medium text-[#7e8488] mb-2">Всего бронирований</h3>
            <p class="text-3xl font-extrabold text-[#8ee30f]">{{ $stats['total_bookings'] }}</p>
        </div>
        <div class="otl-surface p-6">
            <h3 class="text-sm font-medium text-[#7e8488] mb-2">Общий доход</h3>
            <p class="text-3xl font-extrabold text-[#8ee30f]">{{ number_format($stats['total_revenue'] ?? 0, 0, '.', ' ') }} ₸</p>
        </div>
        <div class="otl-surface p-6">
            <h3 class="text-sm font-medium text-[#7e8488] mb-2">Ожидающие отзывы</h3>
            <p class="text-3xl font-extrabold text-yellow-300">{{ $stats['pending_reviews'] }}</p>
        </div>
        <div class="otl-surface p-6">
            <h3 class="text-sm font-medium text-[#7e8488] mb-2">Ожидающие подтверждения</h3>
            <p class="text-3xl font-extrabold text-yellow-300">{{ $stats['pending_bookings'] }}</p>
        </div>
        <div class="otl-surface p-6">
            <h3 class="text-sm font-medium text-[#7e8488] mb-2">Всего отелей</h3>
            <p class="text-3xl font-extrabold text-white">{{ $stats['total_hotels'] }}</p>
        </div>
        <div class="otl-surface p-6">
            <h3 class="text-sm font-medium text-[#7e8488] mb-2">Всего пользователей</h3>
            <p class="text-3xl font-extrabold text-white">{{ $stats['total_users'] }}</p>
        </div>
    </div>

    {{-- Top Cities --}}
    <div class="otl-surface p-6 mb-8">
        <h2 class="text-lg font-bold text-white mb-4">Популярные города</h2>
        <div class="space-y-2">
            @forelse($stats['top_cities'] as $city)
                <div class="flex justify-between items-center py-2 border-b border-white/5 last:border-0">
                    <span class="text-gray-200">{{ $city->city }}</span>
                    <span class="font-semibold text-[#8ee30f]">{{ $city->count }} {{ $city->count == 1 ? 'отель' : ($city->count < 5 ? 'отеля' : 'отелей') }}</span>
                </div>
            @empty
                <p class="text-[#7e8488]">Нет данных</p>
            @endforelse
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $links = [
                ['admin.hotels.index', 'Управление отелями', 'Создание, редактирование, удаление'],
                ['admin.rooms.index', 'Управление номерами', 'Создание, редактирование, удаление'],
                ['admin.bookings.index', 'Управление бронированиями', 'Просмотр и управление'],
                ['admin.users.index', 'Управление пользователями', 'Блокировка / разблокировка'],
                ['admin.reviews.index', 'Модерация отзывов', 'Одобрение / отклонение'],
            ];
        @endphp
        @foreach($links as [$route, $title, $desc])
            <a href="{{ route($route) }}" class="otl-surface p-6 hover:ring-2 hover:ring-[#8ee30f]/40 transition">
                <h3 class="font-semibold text-white mb-1">{{ $title }}</h3>
                <p class="text-sm text-[#7e8488]">{{ $desc }}</p>
            </a>
        @endforeach
    </div>
</div>
@endsection
