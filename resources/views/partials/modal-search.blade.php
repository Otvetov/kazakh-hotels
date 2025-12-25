<div id="searchModal" class="modal hidden" style="display: none;">
    <div class="modal-box" style="max-width: 42rem;">
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-gray-900 text-xl font-bold">Выберите направление</h2>
            <button
                onclick="closeModals()"
                class="p-2 hover:bg-gray-100 rounded-lg transition-colors"
            >
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Search Input -->
        <div class="p-6">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input
                    id="citySelect"
                    type="text"
                    value=""
                    placeholder="Поиск города или отеля..."
                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#38b000] focus:border-transparent bg-white text-gray-900"
                    autofocus
                />
            </div>
        </div>

        <!-- Search Results -->
        <div id="search-results" class="px-6 pb-4 hidden">
            <p class="text-sm text-gray-500 mb-2">Результаты поиска</p>
            <div id="cities-list-results" class="space-y-2"></div>
        </div>

        <!-- Popular Cities -->
        <div id="popular-cities" class="px-6 pb-6">
            <p class="text-sm text-gray-500 mb-3">Популярные направления</p>
            <div class="space-y-2">
                @if(isset($popularCities) && $popularCities->count() > 0)
                    @foreach($popularCities as $city)
                        <button
                            onclick="selectCityAndClose('{{ $city['name'] }}')"
                            class="w-full flex items-start gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors text-left"
                        >
                            <svg class="w-5 h-5 text-[#38b000] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div>
                                <div class="text-gray-900 font-medium">{{ $city['name'] }}</div>
                                <div class="text-sm text-gray-500">{{ $city['description'] }}</div>
                            </div>
                        </button>
                    @endforeach
                @else
                    <p class="text-sm text-gray-400">Нет доступных направлений</p>
                @endif
            </div>
        </div>
    </div>
</div>

