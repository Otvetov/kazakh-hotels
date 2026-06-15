@extends('layouts.app')

@section('title', __('messages.admin_edit_room') . ' - Kazakh Hotels')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-extrabold text-white mb-6">{{ __('messages.admin_edit_room') }}</h1>

    <form action="{{ route('admin.rooms.update', $room) }}" method="POST" class="otl-surface p-6">
        @csrf
        @method('PUT')
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.hotel') }}</label>
                <select name="hotel_id" required class="field-input" style="color-scheme: dark;">
                    @foreach($hotels as $hotel)
                        <option value="{{ $hotel->id }}" {{ old('hotel_id', $room->hotel_id) == $hotel->id ? 'selected' : '' }}>{{ $hotel->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.admin_room_name') }}</label>
                <input type="text" name="name" required value="{{ old('name', $room->name) }}" class="field-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.admin_price_per_night') }}</label>
                <input type="number" name="price_per_night" required step="0.01" min="0" value="{{ old('price_per_night', $room->price_per_night) }}" class="field-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.admin_capacity') }}</label>
                <input type="number" name="capacity" required min="1" value="{{ old('capacity', $room->capacity) }}" class="field-input">
            </div>
            <label class="flex items-center gap-2 text-gray-200">
                <input type="checkbox" name="is_available" value="1" {{ old('is_available', $room->is_available) ? 'checked' : '' }} class="w-4 h-4 rounded bg-[#141516] border-white/20 accent-[#8ee30f]">
                <span>{{ __('messages.admin_available_for_booking') }}</span>
            </label>
            <div class="flex gap-3">
                <button type="submit" class="btn-accent flex-1 py-3">{{ __('messages.save') }}</button>
                <a href="{{ route('admin.rooms.index') }}" class="btn-dark px-6 py-3">{{ __('messages.cancel') }}</a>
            </div>
        </div>
    </form>
</div>
@endsection
