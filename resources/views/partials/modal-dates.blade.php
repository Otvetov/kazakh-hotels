<div id="dateModal" class="modal hidden">
    <div class="modal-box">
        <div class="flex justify-between items-center mb-6">
            <h3 class="modal-title text-xl font-bold text-gray-900">Даты поездки</h3>
            <button onclick="closeModals()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Заезд</label>
                <input 
                    type="date" 
                    id="checkIn" 
                    min="{{ date('Y-m-d') }}"
                    value="{{ request('check_in') }}"
                    class="modal-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#38b000] focus:border-[#38b000] outline-none"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Выезд</label>
                <input 
                    type="date" 
                    id="checkOut" 
                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                    value="{{ request('check_out') }}"
                    class="modal-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#38b000] focus:border-[#38b000] outline-none"
                >
            </div>
        </div>

        <button onclick="saveDates()" class="modal-btn w-full py-3 bg-[#38b000] text-white rounded-xl hover:bg-[#2d8c00] transition font-semibold mt-6">
            Готово
        </button>
    </div>
</div>

