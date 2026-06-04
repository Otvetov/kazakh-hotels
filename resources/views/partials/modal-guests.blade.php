<div id="guestsModal" class="modal hidden" style="display: none;">
    <div class="modal-box" style="max-width: 30rem;">
        <!-- Header -->
        <div class="flex items-center justify-center relative p-6 border-b border-white/10">
            <h2 class="text-white text-lg font-bold">Гости и номера</h2>
            <button
                onclick="closeModals()"
                class="absolute right-5 top-1/2 -translate-y-1/2 p-2 bg-white/5 hover:bg-white/10 rounded-full transition-colors"
            >
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6 space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <div class="font-semibold text-white">Гости</div>
                    <div class="text-sm text-[#7e8488]">Количество гостей</div>
                </div>
                <div class="flex items-center gap-4">
                    <button
                        id="guestsMinusBtn"
                        onclick="changeGuests(-1)"
                        class="w-10 h-10 flex items-center justify-center bg-[#2a2b2c] rounded-full hover:bg-[#343536] transition disabled:opacity-40 disabled:cursor-not-allowed"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                    </button>
                    <span id="guestsCount" class="text-white min-w-[1.5rem] text-center font-semibold text-lg">{{ request('guests', 2) }}</span>
                    <button
                        id="guestsPlusBtn"
                        onclick="changeGuests(1)"
                        class="w-10 h-10 flex items-center justify-center bg-[#2a2b2c] rounded-full hover:bg-[#343536] transition disabled:opacity-40 disabled:cursor-not-allowed"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <div>
                    <div class="font-semibold text-white">Номера</div>
                    <div class="text-sm text-[#7e8488]">Количество номеров</div>
                </div>
                <div class="flex items-center gap-4">
                    <button
                        id="roomsMinusBtn"
                        onclick="changeRooms(-1)"
                        class="w-10 h-10 flex items-center justify-center bg-[#2a2b2c] rounded-full hover:bg-[#343536] transition disabled:opacity-40 disabled:cursor-not-allowed"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                    </button>
                    <span id="roomsCount" class="text-white min-w-[1.5rem] text-center font-semibold text-lg">{{ request('rooms', 1) }}</span>
                    <button
                        id="roomsPlusBtn"
                        onclick="changeRooms(1)"
                        class="w-10 h-10 flex items-center justify-center bg-[#2a2b2c] rounded-full hover:bg-[#343536] transition disabled:opacity-40 disabled:cursor-not-allowed"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end gap-3 p-6 border-t border-white/10">
            <button onclick="closeModals()" class="btn-dark px-6 py-2.5">Отмена</button>
            <button onclick="saveGuests()" class="btn-accent px-7 py-2.5">Применить</button>
        </div>
    </div>
</div>
