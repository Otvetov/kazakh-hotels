@extends('layouts.app')

@section('title', 'Create Hotel - Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Create Hotel</h1>

    <form action="{{ route('admin.hotels.store') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
        @csrf

        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium mb-2">Name</label>
                <input type="text" name="name" required value="{{ old('name') }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">City</label>
                <input type="text" name="city" required value="{{ old('city') }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Address</label>
                <textarea name="address" required rows="3"
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">{{ old('address') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Description</label>
                <textarea name="description" rows="5"
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Rating</label>
                <input type="number" name="rating" step="0.1" min="0" max="5" value="{{ old('rating') }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Image</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 px-6 py-3 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8a00] transition">
                    Create Hotel
                </button>
                <a href="{{ route('admin.hotels.index') }}" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection


