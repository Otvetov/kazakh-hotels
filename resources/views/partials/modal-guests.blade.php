<div id="guestsModal" class="modal hidden">
    <div class="modal-box">
        <div class="flex justify-between items-center mb-6">
            <h3 class="modal-title text-xl font-bold text-gray-900">Гости и номера</h3>
            <button onclick="closeModals()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="flex items-center gap-4">
                    <button onclick="changeGuests(-1)" class="w-10 h-10 rounded-full border-2 border-gray-300 flex items-center justify-center hover:bg-gray-100 transition font-semibold text-gray-700">−</button>
                    <span id="guestsCount" class="w-8 text-center font-semibold text-lg text-gray-900">{{ request('guests', 2) }}</span>
                    <button onclick="changeGuests(1)" class="w-10 h-10 rounded-full border-2 border-gray-300 flex items-center justify-center hover:bg-gray-100 transition font-semibold text-gray-700">+</button>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <div>
                    <div class="font-semibold text-gray-900">Номера</div>
                    <div class="text-sm text-gray-500">Количество номеров</div>
                </div>
                <div class="flex items-center gap-4">
                    <button onclick="changeRooms(-1)" class="w-10 h-10 rounded-full border-2 border-gray-300 flex items-center justify-center hover:bg-gray-100 transition font-semibold text-gray-700">−</button>
                    <span id="roomsCount" class="w-8 text-center font-semibold text-lg text-gray-900">{{ request('rooms', 1) }}</span>
                    <button onclick="changeRooms(1)" class="w-10 h-10 rounded-full border-2 border-gray-300 flex items-center justify-center hover:bg-gray-100 transition font-semibold text-gray-700">+</button>
                </div>
            </div>
        </div>

        <button onclick="saveGuests()" class="modal-btn w-full py-3 bg-[#38b000] text-white rounded-xl hover:bg-[#2d8c00] transition font-semibold mt-6">
            Готово
        </button>
    </div>
</div>

