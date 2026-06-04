<div id="dateModal" class="modal hidden" style="display: none;">
    <div class="modal-box" style="max-width: 30rem;">
        <!-- Header -->
        <div class="flex items-center justify-center relative p-6 border-b border-white/10">
            <h3 class="text-white text-lg font-bold">Даты поездки</h3>
            <button onclick="closeModals()" class="absolute right-5 top-1/2 -translate-y-1/2 p-2 bg-white/5 hover:bg-white/10 rounded-full transition-colors">
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6">
            {{-- Month navigation --}}
            <div class="flex items-center justify-between mb-4">
                <button type="button" onclick="calPrevMonth()" id="calPrev" class="p-2 rounded-full hover:bg-white/10 transition disabled:opacity-30 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <span id="calMonthLabel" class="text-white font-semibold"></span>
                <button type="button" onclick="calNextMonth()" class="p-2 rounded-full hover:bg-white/10 transition">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>

            {{-- Weekday header --}}
            <div class="grid grid-cols-7 gap-1 mb-2 text-center text-xs text-[#7e8488]">
                <span>Пн</span><span>Вт</span><span>Ср</span><span>Чт</span><span>Пт</span>
                <span class="text-[#f04141]/70">Сб</span><span class="text-[#f04141]/70">Вс</span>
            </div>

            {{-- Days grid --}}
            <div id="calDays" class="grid grid-cols-7 gap-1"></div>

            {{-- Selected range hint --}}
            <p id="calHint" class="text-sm text-[#7e8488] mt-4 text-center">Выберите дату заезда</p>
        </div>

        {{-- Hidden inputs consumed by saveDates() --}}
        <input type="hidden" id="checkIn" value="{{ request('check_in') }}">
        <input type="hidden" id="checkOut" value="{{ request('check_out') }}">

        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 p-6 border-t border-white/10">
            <button type="button" onclick="calClear()" class="btn-dark px-6 py-2.5">Очистить</button>
            <button type="button" onclick="saveDates()" class="btn-accent px-7 py-2.5">Применить</button>
        </div>
    </div>
</div>
