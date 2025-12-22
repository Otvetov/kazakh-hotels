@extends('layouts.app')

@section('title', 'Kazakh Hotels - Отели для путешествий по Казахстану')

@section('content')
<!-- Hero Section with Search -->
<div class="bg-gradient-to-b from-blue-50 to-white py-12 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                Отели для путешествий по Казахстану
            </h1>
            <p class="text-lg text-gray-600">Найдите идеальный отель для вашего отдыха</p>
        </div>

        <!-- Main Search Form -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-6 lg:p-8 border border-gray-200">
                <form action="{{ route('home') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- City/Hotel Search -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Город, отель или направление</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Куда вы хотите поехать?"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0066cc] focus:border-[#0066cc] outline-none">
                        </div>

                        <!-- Date Range -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Даты заезда</label>
                            <input type="date" name="check_in" value="{{ request('check_in') }}" 
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0066cc] focus:border-[#0066cc] outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Даты выезда</label>
                            <input type="date" name="check_out" value="{{ request('check_out') }}" 
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0066cc] focus:border-[#0066cc] outline-none">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Гости</label>
                                <input type="number" name="guests" value="{{ request('guests', 2) }}" min="1" 
                                       class="w-24 px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0066cc] focus:border-[#0066cc] outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Номера</label>
                                <input type="number" name="rooms" value="{{ request('rooms', 1) }}" min="1" 
                                       class="w-24 px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0066cc] focus:border-[#0066cc] outline-none">
                            </div>
                        </div>
                        <button type="submit" class="px-8 py-3 bg-[#0066cc] text-white rounded-md hover:bg-[#0052a3] transition font-semibold text-lg">
                            Найти
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Benefits Section -->
<div class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Выгоды для старта</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="border border-gray-200 rounded-lg p-6">
                <div class="text-sm text-gray-600 mb-2">Промокод</div>
                <div class="font-bold text-lg mb-2">12APP</div>
                <p class="text-sm text-gray-700 mb-3">Получите скидку 12% на первую бронь в приложении</p>
                <div class="text-sm text-gray-600">Максимум — 1000 ₸</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-6">
                <div class="text-sm text-gray-600 mb-2">Промокод</div>
                <div class="font-bold text-lg mb-2">10WEB</div>
                <p class="text-sm text-gray-700 mb-3">Забронируйте в первый раз со скидкой 10%</p>
                <div class="text-sm text-gray-600">Максимум — 1000 ₸</div>
            </div>
            <div class="border border-gray-200 rounded-lg p-6">
                <div class="text-sm text-gray-600 mb-2">Промокод</div>
                <div class="font-bold text-lg mb-2">2K40</div>
                <p class="text-sm text-gray-700 mb-3">Сэкономьте 2000 ₸ на бронировании от 40 000 ₽</p>
            </div>
        </div>
    </div>
</div>

<!-- Hotels Grid -->
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Популярные отели</h2>
        
        <div id="hotels-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @include('partials.hotel-cards', ['hotels' => $hotels])
        </div>

        @if($hotels->hasMorePages())
            <div class="mt-8 text-center">
                <button id="load-more" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition font-medium">
                    Загрузить еще
                </button>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadMoreBtn = document.getElementById('load-more');
    if (loadMoreBtn) {
        let page = 2;
        loadMoreBtn.addEventListener('click', function() {
            fetch(`{{ route('home') }}?page=${page}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('hotels-container').insertAdjacentHTML('beforeend', data.html);
                if (!data.has_more) {
                    loadMoreBtn.remove();
                }
                page++;
            });
        });
    }
});
</script>
@endsection
