@extends('layouts.app')

@section('title', __('messages.admin_panel') . ' - Kazakh Hotels')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-extrabold text-white mb-6">{{ __('messages.admin_panel') }}</h1>

    {{-- Statistics --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        <div class="otl-surface p-6">
            <h3 class="text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.admin_total_bookings') }}</h3>
            <p class="text-3xl font-extrabold text-[#8ee30f]">{{ $stats['total_bookings'] }}</p>
        </div>
        <div class="otl-surface p-6">
            <h3 class="text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.admin_total_revenue') }}</h3>
            <p class="text-3xl font-extrabold text-[#8ee30f]">{{ number_format($stats['total_revenue'] ?? 0, 0, '.', ' ') }} ₸</p>
        </div>
        <div class="otl-surface p-6">
            <h3 class="text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.admin_pending_reviews') }}</h3>
            <p class="text-3xl font-extrabold text-yellow-300">{{ $stats['pending_reviews'] }}</p>
        </div>
        <div class="otl-surface p-6">
            <h3 class="text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.admin_pending_bookings') }}</h3>
            <p class="text-3xl font-extrabold text-yellow-300">{{ $stats['pending_bookings'] }}</p>
        </div>
        <div class="otl-surface p-6">
            <h3 class="text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.admin_total_hotels') }}</h3>
            <p class="text-3xl font-extrabold text-white">{{ $stats['total_hotels'] }}</p>
        </div>
        <div class="otl-surface p-6">
            <h3 class="text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.admin_total_users') }}</h3>
            <p class="text-3xl font-extrabold text-white">{{ $stats['total_users'] }}</p>
        </div>
    </div>

    {{-- Top Cities --}}
    <div class="otl-surface p-6 mb-8">
        <h2 class="text-lg font-bold text-white mb-4">{{ __('messages.admin_popular_cities') }}</h2>
        <div class="space-y-2">
            @forelse($stats['top_cities'] as $city)
                <div class="flex justify-between items-center py-2 border-b border-white/5 last:border-0">
                    <span class="text-gray-200">{{ $city->city }}</span>
                    <span class="font-semibold text-[#8ee30f]">{{ trans_choice('messages.admin_hotels_plural', $city->count, ['count' => $city->count]) }}</span>
                </div>
            @empty
                <p class="text-[#7e8488]">{{ __('messages.admin_no_data') }}</p>
            @endforelse
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $links = [
                ['admin.hotels.index', __('messages.admin_manage_hotels'), __('messages.admin_hotels_desc')],
                ['admin.rooms.index', __('messages.admin_manage_rooms'), __('messages.admin_rooms_desc')],
                ['admin.bookings.index', __('messages.admin_manage_bookings'), __('messages.admin_bookings_desc')],
                ['admin.users.index', __('messages.admin_manage_users'), __('messages.admin_users_desc')],
                ['admin.reviews.index', __('messages.admin_moderate_reviews'), __('messages.admin_reviews_desc')],
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
