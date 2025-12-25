@extends('layouts.app')

@section('title', 'Manage Rooms - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Manage Rooms</h1>
        <a href="{{ route('admin.rooms.create') }}" class="px-4 py-2 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8a00] transition">
            Add Room
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Hotel</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Room Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Capacity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Price/Night</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Available</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($rooms as $room)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4">{{ $room->hotel->name }}</td>
                        <td class="px-6 py-4 font-semibold">{{ $room->name }}</td>
                        <td class="px-6 py-4">{{ $room->capacity }}</td>
                        <td class="px-6 py-4">{{ number_format($room->price_per_night, 0) }} ₸</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded {{ $room->is_available ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                {{ $room->is_available ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.rooms.edit', $room) }}" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">Edit</a>
                                <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure?')" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No rooms found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $rooms->links() }}
    </div>
</div>
@endsection


