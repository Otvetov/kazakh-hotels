@extends('layouts.app')

@section('title', 'Управление отелями - Админ')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Управление отелями</h1>
        <a href="{{ route('admin.hotels.create') }}" class="px-4 py-2 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8a00] transition">
            Добавить отель
        </a>
    </div>

    <div class="bg-whitebg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200border-gray-700">
        <table class="w-full">
            <thead class="bg-gray-50bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Изображение</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Название</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Город</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Рейтинг</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Номера</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Действия</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200divide-gray-700">
                @forelse($hotels as $hotel)
                    <tr class="hover:bg-gray-50hover:bg-gray-700">
                        <td class="px-6 py-4">
                            @if($hotel->image)
                                <img src="{{ $hotel->image_url }}" alt="{{ $hotel->name }}" class="w-16 h-16 object-cover rounded">
                            @else
                                <div class="w-16 h-16 bg-gray-200bg-gray-600 rounded"></div>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-semibold">{{ $hotel->name }}</td>
                        <td class="px-6 py-4">{{ $hotel->city }}</td>
                        <td class="px-6 py-4">{{ $hotel->rating ?? 'Н/Д' }}</td>
                        <td class="px-6 py-4">{{ $hotel->rooms->count() }}</td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.hotels.edit', $hotel) }}" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">Редактировать</a>
                                <form action="{{ route('admin.hotels.destroy', $hotel) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Вы уверены?')" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">Удалить</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500text-gray-400">Отели не найдены</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $hotels->links() }}
    </div>
</div>
@endsection

