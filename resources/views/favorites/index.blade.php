@extends('layouts.app')

@section('title', 'Избранное - Kazakh Hotels')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="otl-surface px-6 py-5 mb-6">
        <h1 class="text-2xl font-extrabold text-white">{{ __('messages.favorites_title') }}</h1>
    </div>

    @if($favorites->count() === 0)
        <div class="otl-surface py-16 px-6 text-center">
            <svg class="w-20 h-20 mx-auto text-[#f04141] fill-current mb-5" viewBox="0 0 24 24">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
            <h2 class="text-white text-xl font-bold mb-2">{{ __('messages.empty_here') }}</h2>
            <p class="text-[#7e8488] mb-6">{{ __('messages.empty_fav_text') }}</p>
            <a href="{{ route('hotels.index') }}" class="btn-accent px-6 py-3">{{ __('messages.for_inspiration') }}</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($favorites as $favorite)
                @php($hotel = $favorite->hotel)
                @include('partials.hotel-card', ['hotel' => $hotel])
            @endforeach
        </div>

        @if($favorites->hasPages())
            <div class="mt-8">
                {{ $favorites->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
