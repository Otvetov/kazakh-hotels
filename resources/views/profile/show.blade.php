@extends('layouts.app')

@section('title', 'Profile - Kazakh Hotels')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">My Profile</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Info -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700 mb-6">
                <div class="flex items-center mb-6">
                    <div class="w-20 h-20 rounded-full bg-[#38b000] flex items-center justify-center text-white text-3xl font-bold mr-4">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-2xl font-semibold">{{ $user->name }}</h2>
                        <p class="text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                    </div>
                </div>

                <button onclick="document.getElementById('edit-modal').classList.remove('hidden')" 
                        class="px-4 py-2 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8a00] transition">
                    Edit Profile
                </button>
            </div>

            <!-- Recent Bookings -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold mb-4">Recent Bookings</h3>
                <div class="space-y-4">
                    @forelse($bookings as $booking)
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold">{{ $booking->room->hotel->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $booking->check_in->format('M d') }} - {{ $booking->check_out->format('M d, Y') }}</p>
                                </div>
                                <span class="text-[#38b000] font-semibold">{{ number_format($booking->total_price, 0) }} ₸</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400">No recent bookings</p>
                    @endforelse
                </div>
                <a href="{{ route('bookings.index') }}" class="mt-4 inline-block text-[#38b000] hover:underline">
                    View all bookings →
                </a>
            </div>
        </div>

        <!-- Settings -->
        <div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold mb-4">Settings</h3>
                
                <div class="flex items-center justify-between mb-4">
                    <span>Dark Mode</span>
                    <button id="darkModeToggle" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Toggle
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="edit-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-2xl font-semibold mb-4">Edit Profile</h3>
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Name</label>
                <input type="text" name="name" value="{{ $user->name }}" required
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
            </div>
            <div class="flex gap-4">
                <button type="submit" class="flex-1 px-4 py-2 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8a00] transition">
                    Save
                </button>
                <button type="button" onclick="document.getElementById('edit-modal').classList.add('hidden')" 
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

