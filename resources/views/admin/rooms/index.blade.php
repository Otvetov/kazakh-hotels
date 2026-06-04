@extends('layouts.app')

@section('title', 'Управление номерами - Админ')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-extrabold text-white">Управление номерами</h1>
        <a href="{{ route('admin.rooms.create') }}" class="btn-accent px-5 py-2.5">Добавить номер</a>
    </div>

    <form method="GET" class="flex gap-3 mb-5">
        <select name="booked" class="field-input max-w-xs" style="color-scheme: dark;">
            <option value="">Все номера</option>
            <option value="1" {{ request('booked') == '1' ? 'selected' : '' }}>Забронированные</option>
            <option value="0" {{ request('booked') == '0' ? 'selected' : '' }}>Свободные</option>
        </select>
        <button type="submit" class="btn-dark px-6">Фильтр</button>
    </form>

    <div class="otl-surface overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#141516] text-[#7e8488]">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">Отель</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">Номер</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">Вместимость</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">Цена/ночь</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">Доступен</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">Бронирования</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">Действия</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($rooms as $room)
                        @php
                            $activeBookings = $room->bookings->filter(function ($booking) {
                                return $booking->status != 'cancelled' && $booking->check_out >= now();
                            });
                        @endphp
                        <tr class="hover:bg-white/5 transition align-top">
                            <td class="px-6 py-4 text-gray-300">{{ $room->hotel->name }}</td>
                            <td class="px-6 py-4 font-semibold text-white">{{ $room->name }}</td>
                            <td class="px-6 py-4 text-gray-300">{{ $room->capacity }}</td>
                            <td class="px-6 py-4 text-[#8ee30f] font-semibold">{{ number_format($room->price_per_night, 0, '.', ' ') }} ₸</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs {{ $room->is_available ? 'bg-[#8ee30f]/15 text-[#8ee30f]' : 'bg-[#f04141]/15 text-[#ff8a8a]' }}">
                                    {{ $room->is_available ? 'Да' : 'Нет' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($activeBookings->count() > 0)
                                    <div class="space-y-1">
                                        @foreach($activeBookings->take(2) as $booking)
                                            <div class="text-xs">
                                                <div class="font-medium text-white">{{ $booking->user->name }}</div>
                                                <div class="text-[#7e8488]">{{ $booking->check_in->format('d.m.Y') }} – {{ $booking->check_out->format('d.m.Y') }}</div>
                                            </div>
                                        @endforeach
                                        @if($activeBookings->count() > 2)
                                            <div class="text-xs text-[#7e8488]">+{{ $activeBookings->count() - 2 }} ещё</div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-[#7e8488] text-sm">Нет активных</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.rooms.edit', $room) }}" class="px-3 py-1.5 bg-[#2a2b2c] text-gray-200 rounded-full hover:bg-[#343536] transition text-xs">Изменить</a>
                                    <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="inline" onsubmit="return confirm('Удалить номер?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-[#f04141]/15 text-[#ff8a8a] rounded-full hover:bg-[#f04141]/25 transition text-xs">Удалить</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-[#7e8488]">Номера не найдены</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($rooms->hasPages())
        <div class="mt-6">{{ $rooms->links() }}</div>
    @endif
</div>
@endsection
