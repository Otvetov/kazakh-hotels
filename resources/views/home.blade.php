@extends('layouts.app')

@section('title', 'Kazakh Hotels')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">

        {{-- RIGHT: SEARCH PANEL --}}
        <div class="lg:w-[400px] lg:order-2">
            <div class="lg:sticky lg:top-24">
                <div class="otl-surface p-6">
                    <h2 class="text-xl font-bold text-white mb-6">{{ __('messages.find_hotel') }}</h2>

                    <div class="space-y-3">
                        {{-- City button --}}
                        <button onclick="openModal('searchModal')" class="search-btn w-full flex items-start gap-3 p-4 bg-[#141516] border border-white/10 rounded-2xl hover:border-[#8ee30f] transition-colors text-left">
                            <svg class="w-5 h-5 text-[#8ee30f] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <span class="label text-xs text-[#7e8488] block mb-0.5">{{ __('messages.destination_label') }}</span>
                                <span id="cityValue" class="value text-white font-medium truncate block">{{ request('city') ?: __('messages.choose_destination') }}</span>
                            </div>
                        </button>

                        {{-- Date button --}}
                        <button onclick="openModal('dateModal')" class="search-btn w-full flex items-start gap-3 p-4 bg-[#141516] border border-white/10 rounded-2xl hover:border-[#8ee30f] transition-colors text-left">
                            <svg class="w-5 h-5 text-[#8ee30f] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <span class="label text-xs text-[#7e8488] block mb-0.5">{{ __('messages.trip_dates') }}</span>
                                <span id="dateValue" class="value text-white font-medium truncate block">
                                    @if(request('check_in') && request('check_out'))
                                        {{ \Carbon\Carbon::parse(request('check_in'))->locale(app()->getLocale())->translatedFormat('j M') }} – {{ \Carbon\Carbon::parse(request('check_out'))->locale(app()->getLocale())->translatedFormat('j M') }}
                                    @else
                                        {{ __('messages.checkin_checkout') }}
                                    @endif
                                </span>
                            </div>
                        </button>

                        {{-- Guests button --}}
                        <button onclick="openModal('guestsModal')" class="search-btn w-full flex items-start gap-3 p-4 bg-[#141516] border border-white/10 rounded-2xl hover:border-[#8ee30f] transition-colors text-left">
                            <svg class="w-5 h-5 text-[#8ee30f] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <span class="label text-xs text-[#7e8488] block mb-0.5">{{ __('messages.guests_and_rooms') }}</span>
                                <span id="guestsValue" class="value text-white font-medium truncate block">
                                    @php
                                        $guests = request('guests', 2);
                                        $rooms = request('rooms', 1);
                                    @endphp
                                    {{ trans_choice('messages.guests_count', $guests, ['count' => $guests]) }}, {{ trans_choice('messages.rooms_count', $rooms, ['count' => $rooms]) }}
                                </span>
                            </div>
                        </button>

                        {{-- Search form --}}
                        <form action="{{ route('hotels.index') }}" method="GET" id="searchForm" class="pt-2">
                            <input type="hidden" name="city" id="cityInput" value="{{ request('city') }}">
                            <input type="hidden" name="check_in" id="checkInInput" value="{{ request('check_in') }}">
                            <input type="hidden" name="check_out" id="checkOutInput" value="{{ request('check_out') }}">
                            <input type="hidden" name="guests" id="guestsInput" value="{{ request('guests', 2) }}">
                            <input type="hidden" name="rooms" id="roomsInput" value="{{ request('rooms', 1) }}">

                            <div id="searchError" class="mb-3 hidden p-3 bg-[#f04141]/10 border border-[#f04141]/30 rounded-xl text-sm text-[#ff8a8a]"></div>

                            <button type="submit" class="w-full flex items-center justify-center gap-2 py-4 bg-[#8ee30f] text-[#0a0a0a] rounded-2xl hover:bg-[#76c406] transition font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <span>{{ __('messages.find') }}</span>
                            </button>
                        </form>

                        {{-- Benefits --}}
                        <div class="mt-4 pt-5 border-t border-white/10 space-y-3 text-sm">
                            <div class="flex items-start gap-2 text-gray-300">
                                <span class="text-[#8ee30f]">✓</span>
                                <span>{{ __('messages.benefit_1') }}</span>
                            </div>
                            <div class="flex items-start gap-2 text-gray-300">
                                <span class="text-[#8ee30f]">✓</span>
                                <span>{{ __('messages.benefit_2') }}</span>
                            </div>
                            <div class="flex items-start gap-2 text-gray-300">
                                <span class="text-[#8ee30f]">✓</span>
                                <span>{{ __('messages.benefit_3') }}</span>
                            </div>
                            <div class="flex items-start gap-2 text-gray-300">
                                <span class="text-[#8ee30f]">✓</span>
                                <span>{{ __('messages.benefit_4') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- LEFT: HOTELS --}}
        <div class="flex-1 lg:order-1">
            <h1 class="text-white mb-6 text-2xl font-extrabold">
                {{ __('messages.home_heading') }}
            </h1>

            <div id="hotels-container" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                @foreach($hotels as $hotel)
                    @include('partials.hotel-card', ['hotel' => $hotel])
                @endforeach
            </div>

            {{-- Load more --}}
            @if($hotels->hasMorePages())
                <div class="text-center mt-8 pb-8">
                    <button id="load-more" class="btn-dark px-8 py-3">
                        {{ __('messages.load_more') }}
                    </button>
                </div>
            @endif
        </div>

    </div>
</div>

{{-- MODALS --}}
@include('partials.modal-search')
@include('partials.modal-dates')
@include('partials.modal-guests')

@include('partials.modals-js')
@endsection
