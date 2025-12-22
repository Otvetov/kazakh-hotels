@extends('layouts.app')

@section('title', 'Admin Dashboard - Kazakh Hotels')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Total Bookings</h3>
            <p class="text-3xl font-bold text-[#38b000]">{{ $stats['total_bookings'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Total Revenue</h3>
            <p class="text-3xl font-bold text-[#38b000]">{{ number_format($stats['total_revenue'], 0) }} ₸</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Pending Reviews</h3>
            <p class="text-3xl font-bold text-yellow-500">{{ $stats['pending_reviews'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Total Hotels</h3>
            <p class="text-3xl font-bold">{{ $stats['total_hotels'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Total Users</h3>
            <p class="text-3xl font-bold">{{ $stats['total_users'] }}</p>
        </div>
    </div>

    <!-- Top Cities -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700 mb-8">
        <h2 class="text-xl font-semibold mb-4">Top Cities</h2>
        <div class="space-y-2">
            @forelse($stats['top_cities'] as $city)
                <div class="flex justify-between items-center">
                    <span>{{ $city->city }}</span>
                    <span class="font-semibold">{{ $city->count }} hotels</span>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400">No data available</p>
            @endforelse
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('admin.hotels.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition text-center">
            <h3 class="font-semibold mb-2">Manage Hotels</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">CRUD operations</p>
        </a>
        <a href="{{ route('admin.rooms.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition text-center">
            <h3 class="font-semibold mb-2">Manage Rooms</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">CRUD operations</p>
        </a>
        <a href="{{ route('admin.users.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition text-center">
            <h3 class="font-semibold mb-2">Manage Users</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Ban/Unban users</p>
        </a>
        <a href="{{ route('admin.reviews.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition text-center">
            <h3 class="font-semibold mb-2">Moderate Reviews</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Approve/Reject</p>
        </a>
    </div>
</div>
@endsection

