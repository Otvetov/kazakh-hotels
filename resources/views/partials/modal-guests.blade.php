<div id="guestsModal" class="modal hidden" style="display: none;">
    <div class="modal-box" style="max-width: 28rem;">
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-[#38b000]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <h2 class="text-gray-900 text-xl font-bold">Гости и номера</h2>
            </div>
            <button
                onclick="closeModals()"
                class="p-2 hover:bg-gray-100 rounded-lg transition-colors"
            >
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <div class="font-semibold text-gray-900">Гости</div>
                    <div class="text-sm text-gray-500">Количество гостей</div>
                </div>
                <div class="flex items-center gap-3">
                    <button
                        id="guestsMinusBtn"
                        onclick="changeGuests(-1)"
                        class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                    </button>
                    <span id="guestsCount" class="text-gray-900 min-w-[2rem] text-center font-semibold text-lg">{{ request('guests', 2) }}</span>
                    <button
                        id="guestsPlusBtn"
                        onclick="changeGuests(1)"
                        class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <div>
                    <div class="font-semibold text-gray-900">Номера</div>
                    <div class="text-sm text-gray-500">Количество номеров</div>
                </div>
                <div class="flex items-center gap-3">
                    <button
                        id="roomsMinusBtn"
                        onclick="changeRooms(-1)"
                        class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                    </button>
                    <span id="roomsCount" class="text-gray-900 min-w-[2rem] text-center font-semibold text-lg">{{ request('rooms', 1) }}</span>
                    <button
                        id="roomsPlusBtn"
                        onclick="changeRooms(1)"
                        class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200">
            <button
                onclick="closeModals()"
                class="px-6 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
            >
                Отмена
            </button>
            <button
                onclick="saveGuests()"
                class="px-6 py-2 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8c00] transition-colors"
            >
                Применить
            </button>
        </div>
    </div>
</div>

