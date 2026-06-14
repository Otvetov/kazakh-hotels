@extends('layouts.app')

@section('title', __('messages.admin_manage_hotels') . ' - Kazakh Hotels')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-extrabold text-white">{{ __('messages.admin_manage_hotels') }}</h1>
        <a href="{{ route('admin.hotels.create') }}" class="btn-accent px-5 py-2.5">{{ __('messages.admin_add_hotel') }}</a>
    </div>

    <div class="otl-surface overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#141516] text-[#7e8488]">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.admin_image') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.admin_name_col') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.admin_city') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.admin_rating') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.admin_rooms_col') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.admin_actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($hotels as $hotel)
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-6 py-4">
                                @if($hotel->image)
                                    <img src="{{ $hotel->image_url }}" alt="{{ $hotel->name }}" class="w-16 h-16 object-cover rounded-xl">
                                @else
                                    <div class="w-16 h-16 bg-[#141516] rounded-xl"></div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-semibold text-white">{{ $hotel->name }}</td>
                            <td class="px-6 py-4 text-gray-300">{{ $hotel->city }}</td>
                            <td class="px-6 py-4">
                                @if($hotel->rating)
                                    <span class="text-[#8ee30f] font-semibold">{{ number_format($hotel->rating, 1) }}</span>
                                @else
                                    <span class="text-[#7e8488]">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-300">{{ $hotel->rooms->count() }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.hotels.edit', $hotel) }}" class="px-3 py-1.5 bg-[#2a2b2c] text-gray-200 rounded-full hover:bg-[#343536] transition text-xs">{{ __('messages.admin_edit') }}</a>
                                    <form action="{{ route('admin.hotels.destroy', $hotel) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('messages.admin_delete_hotel_confirm') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-[#f04141]/15 text-[#ff8a8a] rounded-full hover:bg-[#f04141]/25 transition text-xs">{{ __('messages.admin_delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-[#7e8488]">{{ __('messages.admin_no_hotels') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($hotels->hasPages())
        <div class="mt-6">{{ $hotels->links() }}</div>
    @endif
</div>
@endsection
