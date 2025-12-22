<div id="searchModal" class="modal hidden">
    <div class="modal-box">
        <div class="flex justify-between items-center mb-4">
            <h3 class="modal-title text-xl font-bold text-gray-900">Выберите город</h3>
            <button onclick="closeModals()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <input 
            id="citySelect"
            type="text"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl mb-4 focus:ring-2 focus:ring-[#38b000] focus:border-[#38b000] outline-none"
            placeholder="Алматы, Астана..."
            autocomplete="off"
        />

        <div id="cities-list" class="space-y-1 max-h-96 overflow-y-auto mb-4">
            @php
                $cities = \App\Models\Hotel::distinct()->pluck('city')->sort();
            @endphp
            @foreach($cities as $cityName)
                <button 
                    onclick="selectCity('{{ $cityName }}')" 
                    class="city-option w-full text-left px-4 py-3 hover:bg-gray-100 rounded-xl text-gray-900 transition font-medium"
                >
                    {{ $cityName }}
                </button>
            @endforeach
        </div>

        <button onclick="saveCity()" class="modal-btn w-full py-3 bg-[#38b000] text-white rounded-xl hover:bg-[#2d8c00] transition font-semibold">
            Готово
        </button>
    </div>
</div>

