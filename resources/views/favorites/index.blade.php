@extends('layouts.app')

@section('title', 'Избранное - Kazakh Hotels')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold mb-8 text-gray-900">Избранное</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($favorites as $favorite)
                @php($hotel = $favorite->hotel)
                @include('partials.hotel-card', ['hotel' => $hotel])
            @empty
                <div class="col-span-full bg-white rounded-2xl shadow-sm p-12 text-center">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg mb-2">Пока нет избранных отелей</p>
                    <p class="text-gray-400 text-sm">Начните добавлять отели в избранное!</p>
                    <a href="{{ route('hotels.index') }}" class="inline-block mt-4 px-6 py-3 bg-[#0066cc] text-white rounded-xl hover:bg-[#0052a3] transition font-semibold">
                        Найти отели
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($favorites->hasPages())
            <div class="mt-8">
                {{ $favorites->links() }}
            </div>
        @endif
    </div>
</div>

@endsection

