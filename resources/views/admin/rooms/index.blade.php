@extends('layouts.app')

@section('title', 'Управление номерами - Админ')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Управление номерами</h1>
        <a href="{{ route('admin.rooms.create') }}" class="px-4 py-2 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8a00] transition">
            Добавить номер
        </a>
    </div>

    <div class="mb-4">
        <form method="GET" class="flex gap-4">
            <select name="booked" class="px-4 py-2 border border-gray-300border-gray-600 rounded-lg bg-whitebg-gray-700">
                <option value="">Все номера</option>
                <option value="1" {{ request('booked') == '1' ? 'selected' : '' }}>Забронированные</option>
                <option value="0" {{ request('booked') == '0' ? 'selected' : '' }}>Свободные</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8a00] transition">Фильтр</button>
        </form>
    </div>

    <div class="bg-whitebg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200border-gray-700">
        <table class="w-full">
            <thead class="bg-gray-50bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Отель</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Название номера</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Вместимость</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Цена/ночь</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Доступен</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Бронирования</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Действия</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200divide-gray-700">
                @forelse($rooms as $room)
                    @php
                        $activeBookings = $room->bookings->filter(function ($booking) {
                            return $booking->status != 'cancelled' && $booking->check_out >= now();
                        });
                    @endphp
                    <tr class="hover:bg-gray-50hover:bg-gray-700">
                        <td class="px-6 py-4">{{ $room->hotel->name }}</td>
                        <td class="px-6 py-4 font-semibold">{{ $room->name }}</td>
                        <td class="px-6 py-4">{{ $room->capacity }}</td>
                        <td class="px-6 py-4">{{ number_format($room->price_per_night, 0) }} ₸</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded {{ $room->is_available ? 'bg-green-100bg-green-900 text-green-800text-green-200' : 'bg-red-100bg-red-900 text-red-800text-red-200' }}">
                                {{ $room->is_available ? 'Да' : 'Нет' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($activeBookings->count() > 0)
                                <div class="space-y-1">
                                    @foreach($activeBookings->take(2) as $booking)
                                        <div class="text-xs">
                                            <div class="font-medium">{{ $booking->user->name }}</div>
                                            <div class="text-gray-600text-gray-400">
                                                {{ $booking->check_in->format('d.m.Y') }} - {{ $booking->check_out->format('d.m.Y') }}
                                            </div>
                                            <span class="px-1 py-0.5 rounded text-xs
                                                {{ $booking->status === 'confirmed' ? 'bg-green-100bg-green-900 text-green-800text-green-200' : 
                                                   ($booking->status === 'cancelled' ? 'bg-red-100bg-red-900 text-red-800text-red-200' : 
                                                    'bg-yellow-100bg-yellow-900 text-yellow-800text-yellow-200') }}">
                                                @if($booking->status === 'confirmed')
                                                    Подтверждено
                                                @elseif($booking->status === 'cancelled')
                                                    Отменено
                                                @else
                                                    Ожидает
                                                @endif
                                            </span>
                                        </div>
                                    @endforeach
                                    @if($activeBookings->count() > 2)
                                        <div class="text-xs text-gray-500text-gray-400">
                                            +{{ $activeBookings->count() - 2 }} еще
                                        </div>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400text-gray-500 text-sm">Нет активных бронирований</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.rooms.edit', $room) }}" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">Редактировать</a>
                                <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Вы уверены?')" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">Удалить</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500text-gray-400">Номера не найдены</td>
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


