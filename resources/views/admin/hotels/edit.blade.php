@extends('layouts.app')

@section('title', 'Edit Hotel - Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Edit Hotel</h1>

    <form action="{{ route('admin.hotels.update', $hotel) }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium mb-2">Name</label>
                <input type="text" name="name" required value="{{ old('name', $hotel->name) }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">City</label>
                <input type="text" name="city" required value="{{ old('city', $hotel->city) }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Address</label>
                <textarea name="address" required rows="3"
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">{{ old('address', $hotel->address) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Description</label>
                <textarea name="description" rows="5"
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">{{ old('description', $hotel->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Rating</label>
                <input type="number" name="rating" step="0.1" min="0" max="5" value="{{ old('rating', $hotel->rating) }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Current Image</label>
                @if($hotel->image)
                    <img src="{{ asset('storage/' . $hotel->image) }}" alt="{{ $hotel->name }}" class="w-32 h-32 object-cover rounded mb-2">
                @endif
                <input type="file" name="image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 px-6 py-3 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8a00] transition">
                    Update Hotel
                </button>
                <a href="{{ route('admin.hotels.index') }}" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

