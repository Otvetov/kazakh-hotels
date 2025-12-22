@extends('layouts.app')

@section('title', 'Home - Kazakh Hotels')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Left Content - Hotel Cards -->
        <div class="flex-1">
            <h1 class="text-3xl font-bold mb-6">Discover Hotels in Kazakhstan</h1>
            
            <div id="hotels-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @include('partials.hotel-cards', ['hotels' => $hotels])
            </div>

            @if($hotels->hasMorePages())
                <div class="mt-8 text-center">
                    <button id="load-more" class="px-6 py-3 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8a00] transition">
                        Load More
                    </button>
                </div>
            @endif
        </div>

        <!-- Right Sidebar - Search Panel -->
        <div class="lg:w-80">
            <div class="sticky top-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold mb-4">Search Hotels</h2>
                
                <form action="{{ route('home') }}" method="GET" class="space-y-4">
                    <!-- City/Hotel Search -->
                    <div>
                        <label class="block text-sm font-medium mb-2">City / Hotel</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search by city or hotel name"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 focus:ring-2 focus:ring-[#38b000] focus:border-transparent">
                    </div>

                    <!-- Date Range -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Check-in</label>
                        <input type="date" name="check_in" value="{{ request('check_in') }}" 
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 focus:ring-2 focus:ring-[#38b000] focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Check-out</label>
                        <input type="date" name="check_out" value="{{ request('check_out') }}" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 focus:ring-2 focus:ring-[#38b000] focus:border-transparent">
                    </div>

                    <!-- Guests -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Guests</label>
                        <input type="number" name="guests" value="{{ request('guests', 1) }}" min="1" 
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 focus:ring-2 focus:ring-[#38b000] focus:border-transparent">
                    </div>

                    <button type="submit" class="w-full px-4 py-3 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8a00] transition font-semibold">
                        Search
                    </button>
                </form>
            </div>
        </div>
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

