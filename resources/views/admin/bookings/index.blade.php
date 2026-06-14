@extends('layouts.app')

@section('title', __('messages.admin_manage_bookings') . ' - Kazakh Hotels')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-extrabold text-white mb-6">{{ __('messages.admin_manage_bookings') }}</h1>

    <form method="GET" class="flex gap-3 mb-5">
        <select name="status" class="field-input max-w-xs" style="color-scheme: dark;">
            <option value="">{{ __('messages.admin_all_statuses') }}</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('messages.admin_status_pending') }}</option>
            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>{{ __('messages.admin_status_confirmed') }}</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('messages.admin_status_cancelled') }}</option>
        </select>
        <button type="submit" class="btn-dark px-6">{{ __('messages.admin_filter') }}</button>
    </form>

    <div class="otl-surface overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#141516] text-[#7e8488]">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.admin_user_col') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.hotel') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.room') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.dates') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.admin_total_col') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.status') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.admin_actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-6 py-4 text-white">{{ $booking->user->name }}</td>
                            <td class="px-6 py-4 text-gray-300">{{ $booking->room->hotel->name }}</td>
                            <td class="px-6 py-4 text-gray-300">{{ $booking->room->name }}</td>
                            <td class="px-6 py-4 text-gray-300">{{ $booking->check_in->format('d.m.Y') }} – {{ $booking->check_out->format('d.m.Y') }}</td>
                            <td class="px-6 py-4 font-semibold text-[#8ee30f]">{{ number_format($booking->total_price, 0, '.', ' ') }} ₸</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs
                                    {{ $booking->status === 'confirmed' ? 'bg-[#8ee30f]/15 text-[#8ee30f]' :
                                       ($booking->status === 'cancelled' ? 'bg-[#f04141]/15 text-[#ff8a8a]' :
                                        'bg-yellow-400/15 text-yellow-300') }}">
                                    @if($booking->status === 'confirmed') {{ __('messages.admin_status_confirmed') }}
                                    @elseif($booking->status === 'cancelled') {{ __('messages.admin_status_cancelled') }}
                                    @else {{ __('messages.admin_status_pending') }} @endif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST" class="inline">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="bg-[#141516] border border-white/10 rounded-lg px-2 py-1.5 text-sm text-white" style="color-scheme: dark;">
                                        <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>{{ __('messages.admin_status_pending') }}</option>
                                        <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>{{ __('messages.admin_status_confirmed') }}</option>
                                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>{{ __('messages.admin_status_cancelled') }}</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-[#7e8488]">{{ __('messages.admin_no_bookings') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($bookings->hasPages())
        <div class="mt-6">{{ $bookings->links() }}</div>
    @endif
</div>
@endsection
