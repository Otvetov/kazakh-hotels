@extends('layouts.app')

@section('title', 'Hotels - Kazakh Hotels')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-6">Hotels Catalog</h1>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Filters Sidebar -->
        <div class="lg:w-64">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold mb-4">Filters</h2>
                
                <form method="GET" action="{{ route('hotels.index') }}" class="space-y-4">
                    <!-- City -->
                    <div>
                        <label class="block text-sm font-medium mb-2">City</label>
                        <select name="city" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                            <option value="">All Cities</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Min Price</label>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" 
                               placeholder="0" min="0"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Max Price</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" 
                               placeholder="100000" min="0"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                    </div>

                    <!-- Rating -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Min Rating</label>
                        <select name="rating" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                            <option value="">Any</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4+ Stars</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3+ Stars</option>
                            <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2+ Stars</option>
                            <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1+ Stars</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full px-4 py-2 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8a00] transition">
                        Apply Filters
                    </button>
                    <a href="{{ route('hotels.index') }}" class="block text-center text-sm text-gray-600 dark:text-gray-400 hover:text-[#38b000]">
                        Clear Filters
                    </a>
                </form>
            </div>
        </div>

        <!-- Hotels List -->
        <div class="flex-1">
            <!-- Sorting -->
            <div class="mb-4 flex justify-between items-center">
                <p class="text-gray-600 dark:text-gray-400">{{ $hotels->total() }} hotels found</p>
                <select id="sort-select" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                    <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Popularity</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @include('partials.hotel-cards', ['hotels' => $hotels])
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $hotels->links() }}
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('sort-select').addEventListener('change', function() {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', this.value);
    window.location.href = url.toString();
});
</script>
@endsection

