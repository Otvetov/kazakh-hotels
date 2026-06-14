@extends('layouts.app')

@section('title', __('messages.admin_manage_rooms') . ' - Kazakh Hotels')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-extrabold text-white">{{ __('messages.admin_manage_rooms') }}</h1>
        <a href="{{ route('admin.rooms.create') }}" class="btn-accent px-5 py-2.5">{{ __('messages.admin_add_room') }}</a>
    </div>

    <form method="GET" class="flex gap-3 mb-5">
        <select name="booked" class="field-input max-w-xs" style="color-scheme: dark;">
            <option value="">{{ __('messages.admin_all_rooms') }}</option>
            <option value="1" {{ request('booked') == '1' ? 'selected' : '' }}>{{ __('messages.admin_booked') }}</option>
            <option value="0" {{ request('booked') == '0' ? 'selected' : '' }}>{{ __('messages.admin_free') }}</option>
        </select>
        <button type="submit" class="btn-dark px-6">{{ __('messages.admin_filter') }}</button>
    </form>

    <div class="otl-surface overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm resp-table">
                <thead class="bg-[#141516] text-[#7e8488]">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.hotel') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.room') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.admin_capacity') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.admin_price_night') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.admin_available') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.admin_bookings_col') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.admin_actions') }}</th>
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
                            <td data-label="{{ __('messages.hotel') }}" class="px-6 py-4 text-gray-300">{{ $room->hotel->name }}</td>
                            <td data-label="{{ __('messages.room') }}" class="px-6 py-4 font-semibold text-white">{{ $room->name }}</td>
                            <td data-label="{{ __('messages.admin_capacity') }}" class="px-6 py-4 text-gray-300">{{ $room->capacity }}</td>
                            <td data-label="{{ __('messages.admin_price_night') }}" class="px-6 py-4 text-[#8ee30f] font-semibold">{{ number_format($room->price_per_night, 0, '.', ' ') }} ₸</td>
                            <td data-label="{{ __('messages.admin_available') }}" class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs {{ $room->is_available ? 'bg-[#8ee30f]/15 text-[#8ee30f]' : 'bg-[#f04141]/15 text-[#ff8a8a]' }}">
                                    {{ $room->is_available ? __('messages.admin_yes') : __('messages.admin_no') }}
                                </span>
                            </td>
                            <td data-label="{{ __('messages.admin_bookings_col') }}" class="px-6 py-4">
                                @if($activeBookings->count() > 0)
                                    <div class="space-y-1">
                                        @foreach($activeBookings->take(2) as $booking)
                                            <div class="text-xs">
                                                <div class="font-medium text-white">{{ $booking->user->name }}</div>
                                                <div class="text-[#7e8488]">{{ $booking->check_in->format('d.m.Y') }} – {{ $booking->check_out->format('d.m.Y') }}</div>
                                            </div>
                                        @endforeach
                                        @if($activeBookings->count() > 2)
                                            <div class="text-xs text-[#7e8488]">+{{ $activeBookings->count() - 2 }} {{ __('messages.admin_more') }}</div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-[#7e8488] text-sm">{{ __('messages.admin_no_active') }}</span>
                                @endif
                            </td>
                            <td data-label="{{ __('messages.admin_actions') }}" class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.rooms.edit', $room) }}" class="px-3 py-1.5 bg-[#2a2b2c] text-gray-200 rounded-full hover:bg-[#343536] transition text-xs">{{ __('messages.admin_edit') }}</a>
                                    <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('messages.admin_delete_room_confirm') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-[#f04141]/15 text-[#ff8a8a] rounded-full hover:bg-[#f04141]/25 transition text-xs">{{ __('messages.admin_delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-[#7e8488]">{{ __('messages.admin_no_rooms') }}</td>
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
