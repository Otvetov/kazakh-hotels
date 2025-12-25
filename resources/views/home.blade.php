@extends('layouts.app')

@section('title', 'Kazakh Hotels')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">

            {{-- RIGHT: SEARCH PANEL --}}
            <div class="lg:w-[400px] lg:order-2">
                <div class="lg:sticky lg:top-24">
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h2 class="text-xl font-semibold mb-6">Найдите отель</h2>

                        {{-- Fake inputs (как в React) --}}
                        <div class="space-y-4">
                            <button onclick="openModal('searchModal')" class="search-btn w-full flex items-start gap-3 p-4 border border-gray-300 rounded-xl hover:border-[#38b000] transition-colors text-left bg-white">
                                <svg class="w-5 h-5 text-[#38b000] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <span class="label text-xs text-gray-500 block mb-0.5">Город, отель или направление</span>
                                    <span id="cityValue" class="value text-gray-900 font-medium truncate block">{{ request('city') ?: 'Выберите направление' }}</span>
                                </div>
                            </button>

                            <button onclick="openModal('dateModal')" class="search-btn w-full flex items-start gap-3 p-4 border border-gray-300 rounded-xl hover:border-[#38b000] transition-colors text-left bg-white">
                                <svg class="w-5 h-5 text-[#38b000] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <span class="label text-xs text-gray-500 block mb-0.5">Даты поездки</span>
                                    <span id="dateValue" class="value text-gray-900 font-medium truncate block">
                                        @if(request('check_in') && request('check_out'))
                                            {{ \Carbon\Carbon::parse(request('check_in'))->format('d M') }} – {{ \Carbon\Carbon::parse(request('check_out'))->format('d M') }}
                                        @else
                                            Заезд – Выезд
                                        @endif
                                    </span>
                                </div>
                            </button>

                            <button onclick="openModal('guestsModal')" class="search-btn w-full flex items-start gap-3 p-4 border border-gray-300 rounded-xl hover:border-[#38b000] transition-colors text-left bg-white">
                                <svg class="w-5 h-5 text-[#38b000] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <span class="label text-xs text-gray-500 block mb-0.5">Гости и номера</span>
                                    <span id="guestsValue" class="value text-gray-900 font-medium truncate block">
                                        @php
                                            $guests = request('guests', 2);
                                            $rooms = request('rooms', 1);
                                        @endphp
                                        {{ $guests }} {{ $guests == 1 ? 'гость' : 'гостей' }}, {{ $rooms }} {{ $rooms == 1 ? 'номер' : 'номеров' }}
                                    </span>
                                </div>
                            </button>

                            <form action="{{ route('hotels.index') }}" method="GET" id="searchForm" class="mt-6">
                                <input type="hidden" name="city" id="cityInput" value="{{ request('city') }}">
                                <input type="hidden" name="check_in" id="checkInInput" value="{{ request('check_in') }}">
                                <input type="hidden" name="check_out" id="checkOutInput" value="{{ request('check_out') }}">
                                <input type="hidden" name="guests" id="guestsInput" value="{{ request('guests', 2) }}">
                                <input type="hidden" name="rooms" id="roomsInput" value="{{ request('rooms', 1) }}">

                                <div id="searchError" class="mb-3 hidden p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600"></div>

                                <button type="submit" class="w-full flex items-center justify-center gap-2 py-4 bg-[#38b000] text-white rounded-xl hover:bg-[#2d8c00] transition font-semibold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <span>Найти</span>
                                </button>
                            </form>
                        </div>

                        {{-- Benefits --}}
                        <div class="mt-6 pt-6 border-t border-gray-200 space-y-3 text-sm">
                            <div class="flex items-start gap-2 text-gray-600">
                                <span class="text-[#38b000]">✓</span>
                                <span>Большой выбор отелей</span>
                            </div>
                            <div class="flex items-start gap-2 text-gray-600">
                                <span class="text-[#38b000]">✓</span>
                                <span>2 миллиона объектов по всему миру</span>
                            </div>
                            <div class="flex items-start gap-2 text-gray-600">
                                <span class="text-[#38b000]">✓</span>
                                <span>Любовь 5 звёзд на любой вкус</span>
                            </div>
                            <div class="flex items-start gap-2 text-gray-600">
                                <span class="text-[#38b000]">✓</span>
                                <span>Поддержка 24/7</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- LEFT: HOTELS --}}
            <div class="flex-1 lg:order-1">
                <h2 class="text-gray-900 dark:text-white mb-6 text-xl font-semibold">
                    Идеи для путешествий по Казахстану
                </h2>

                <div id="hotels-container" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($hotels as $hotel)
                        @include('partials.hotel-card', ['hotel' => $hotel])
                    @endforeach
                </div>

                {{-- Load more --}}
                @if($hotels->hasMorePages())
                    <div class="text-center mt-8 pb-8">
                        <button id="load-more" class="px-8 py-3 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:border-[#38b000] hover:text-[#38b000] transition-colors">
                            Загрузить ещё
                        </button>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- MODALS --}}
@include('partials.modal-search')
@include('partials.modal-dates')
@include('partials.modal-guests')

@include('partials.modals-js')
@endsection
