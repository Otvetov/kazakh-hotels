@extends('layouts.app')

@section('title', 'Панель администратора - Kazakh Hotels')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Панель администратора</h1>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Всего бронирований</h3>
            <p class="text-3xl font-bold text-[#38b000]">{{ $stats['total_bookings'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Общий доход</h3>
            <p class="text-3xl font-bold text-[#38b000]">{{ number_format($stats['total_revenue'], 0) }} ₸</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Ожидающие отзывы</h3>
            <p class="text-3xl font-bold text-yellow-500">{{ $stats['pending_reviews'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Ожидающие прибытия</h3>
            <p class="text-3xl font-bold text-yellow-500">{{ $stats['pending_bookings'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Всего отелей</h3>
            <p class="text-3xl font-bold">{{ $stats['total_hotels'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h3 class="text-sm font-medium text-gray-600 mb-2">Всего пользователей</h3>
            <p class="text-3xl font-bold">{{ $stats['total_users'] }}</p>
        </div>
    </div>

    <!-- Top Cities -->
    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 mb-8">
        <h2 class="text-xl font-semibold mb-4">Популярные города</h2>
        <div class="space-y-2">
            @forelse($stats['top_cities'] as $city)
                <div class="flex justify-between items-center">
                    <span>{{ $city->city }}</span>
                    <span class="font-semibold">{{ $city->count }} {{ $city->count == 1 ? 'отель' : ($city->count < 5 ? 'отеля' : 'отелей') }}</span>
                </div>
            @empty
                <p class="text-gray-500">Нет данных</p>
            @endforelse
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('admin.hotels.index') }}" class="bg-white rounded-lg shadow-md p-6 border border-gray-200 hover:shadow-lg transition text-center">
            <h3 class="font-semibold mb-2">Управление отелями</h3>
            <p class="text-sm text-gray-600">Создание, редактирование, удаление</p>
        </a>
        <a href="{{ route('admin.rooms.index') }}" class="bg-white rounded-lg shadow-md p-6 border border-gray-200 hover:shadow-lg transition text-center">
            <h3 class="font-semibold mb-2">Управление номерами</h3>
            <p class="text-sm text-gray-600">Создание, редактирование, удаление</p>
        </a>
        <a href="{{ route('admin.bookings.index') }}" class="bg-white rounded-lg shadow-md p-6 border border-gray-200 hover:shadow-lg transition text-center">
            <h3 class="font-semibold mb-2">Управление бронированиями</h3>
            <p class="text-sm text-gray-600">Просмотр и управление</p>
        </a>
        <a href="{{ route('admin.users.index') }}" class="bg-white rounded-lg shadow-md p-6 border border-gray-200 hover:shadow-lg transition text-center">
            <h3 class="font-semibold mb-2">Управление пользователями</h3>
            <p class="text-sm text-gray-600">Блокировка/Разблокировка</p>
        </a>
        <a href="{{ route('admin.reviews.index') }}" class="bg-white rounded-lg shadow-md p-6 border border-gray-200 hover:shadow-lg transition text-center">
            <h3 class="font-semibold mb-2">Модерация отзывов</h3>
            <p class="text-sm text-gray-600">Одобрение/Отклонение</p>
        </a>
    </div>
</div>
@endsection
