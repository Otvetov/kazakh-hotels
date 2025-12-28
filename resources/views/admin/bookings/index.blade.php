@extends('layouts.app')

@section('title', 'Управление бронированиями - Админ')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Управление бронированиями</h1>

    <div class="mb-4">
        <form method="GET" class="flex gap-4">
            <select name="status" class="px-4 py-2 border border-gray-300border-gray-600 rounded-lg bg-whitebg-gray-700">
                <option value="">Все статусы</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Ожидает</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Подтверждено</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Отменено</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8a00] transition">Фильтр</button>
        </form>
    </div>

    <div class="bg-whitebg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200border-gray-700">
        <table class="w-full">
            <thead class="bg-gray-50bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Пользователь</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Отель</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Номер</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Даты</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Итого</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Статус</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Действия</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200divide-gray-700">
                @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50hover:bg-gray-700">
                        <td class="px-6 py-4">{{ $booking->user->name }}</td>
                        <td class="px-6 py-4">{{ $booking->room->hotel->name }}</td>
                        <td class="px-6 py-4">{{ $booking->room->name }}</td>
                        <td class="px-6 py-4 text-sm">
                            {{ $booking->check_in->format('d.m.Y') }} - {{ $booking->check_out->format('d.m.Y') }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-[#38b000]">{{ number_format($booking->total_price, 0) }} ₸</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-sm
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
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST" class="inline">
                                @csrf
                                <select name="status" onchange="this.form.submit()" class="px-2 py-1 border border-gray-300border-gray-600 rounded bg-whitebg-gray-700 text-sm">
                                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Ожидает</option>
                                    <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Подтверждено</option>
                                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Отменено</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500text-gray-400">Бронирования не найдены</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $bookings->links() }}
    </div>
</div>
@endsection


