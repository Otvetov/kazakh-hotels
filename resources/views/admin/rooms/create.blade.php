@extends('layouts.app')

@section('title', 'Create Room - Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Create Room</h1>

    <form action="{{ route('admin.rooms.store') }}" method="POST" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
        @csrf

        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium mb-2">Hotel</label>
                <select name="hotel_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                    <option value="">Select hotel...</option>
                    @foreach($hotels as $hotel)
                        <option value="{{ $hotel->id }}" {{ old('hotel_id') == $hotel->id ? 'selected' : '' }}>{{ $hotel->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Room Name</label>
                <input type="text" name="name" required value="{{ old('name') }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Price per Night (₸)</label>
                <input type="number" name="price_per_night" required step="0.01" min="0" value="{{ old('price_per_night') }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Capacity</label>
                <input type="number" name="capacity" required min="1" value="{{ old('capacity') }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}
                           class="rounded border-gray-300 dark:border-gray-600">
                    <span class="ml-2">Available</span>
                </label>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 px-6 py-3 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8a00] transition">
                    Create Room
                </button>
                <a href="{{ route('admin.rooms.index') }}" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

