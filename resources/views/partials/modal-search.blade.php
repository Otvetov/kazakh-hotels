<div id="searchModal" class="modal hidden" style="display: none;">
    <div class="modal-box" style="max-width: 42rem;">
        <!-- Header -->
        <div class="flex items-center justify-center relative p-6 border-b border-white/10">
            <h2 class="text-white text-lg font-bold">{{ __('messages.where_stay') }}</h2>
            <button
                onclick="closeModals()"
                class="absolute right-5 top-1/2 -translate-y-1/2 p-2 bg-white/5 hover:bg-white/10 rounded-full transition-colors"
            >
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Search Input -->
        <div class="p-6">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-[#7e8488]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input
                    id="citySelect"
                    type="text"
                    value=""
                    placeholder="{{ __('messages.destination_label') }}"
                    class="w-full pl-12 pr-4 py-3.5 bg-[#141516] border border-white/10 rounded-2xl focus:outline-none focus:border-[#8ee30f] text-white placeholder-[#7e8488]"
                    autofocus
                />
            </div>
        </div>

        <!-- Search Results -->
        <div id="search-results" class="px-6 pb-4 hidden">
            <p class="text-sm text-[#7e8488] mb-2">{{ __('messages.search_results') }}</p>
            <div id="cities-list-results" class="space-y-1"></div>
        </div>

        <!-- Popular Cities -->
        <div id="popular-cities" class="px-6 pb-6">
            <p class="text-sm font-semibold text-[#7e8488] mb-3">{{ __('messages.popular_destinations') }}</p>
            <div class="space-y-1">
                @if(isset($popularCities) && $popularCities->count() > 0)
                    @foreach($popularCities as $city)
                        <button
                            onclick="selectCityAndClose('{{ $city['name'] }}')"
                            class="w-full flex items-center gap-3 p-3 hover:bg-white/5 rounded-2xl transition-colors text-left"
                        >
                            <span class="w-10 h-10 flex items-center justify-center bg-[#141516] rounded-full flex-shrink-0">
                                <svg class="w-5 h-5 text-[#8ee30f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </span>
                            <div>
                                <div class="text-white font-medium">{{ $city['name'] }}</div>
                                <div class="text-sm text-[#7e8488]">{{ $city['description'] }}</div>
                            </div>
                        </button>
                    @endforeach
                @else
                    <p class="text-sm text-[#7e8488]">{{ __('messages.no_destinations') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
