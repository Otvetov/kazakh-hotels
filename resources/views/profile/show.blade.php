@extends('layouts.app')

@section('title', 'Профиль - Kazakh Hotels')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Profile card --}}
            <div class="otl-surface p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-16 h-16 rounded-full bg-[#8ee30f] flex items-center justify-center text-[#0a0a0a] text-2xl font-bold flex-shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-xl font-bold text-white truncate">{{ $user->name }}</h2>
                            <p class="text-[#7e8488] truncate">{{ $user->email }}</p>
                        </div>
                    </div>
                    <button onclick="document.getElementById('edit-modal').classList.remove('hidden')"
                            class="p-2.5 rounded-full hover:bg-white/10 transition flex-shrink-0" aria-label="Редактировать">
                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-4">
                <div class="otl-surface p-5 text-center">
                    <div class="text-3xl font-extrabold text-[#8ee30f]">{{ $stats['bookings'] }}</div>
                    <div class="text-sm text-[#7e8488] mt-1">{{ __('messages.stat_bookings') }}</div>
                </div>
                <div class="otl-surface p-5 text-center">
                    <div class="text-3xl font-extrabold text-[#8ee30f]">{{ $stats['favorites'] }}</div>
                    <div class="text-sm text-[#7e8488] mt-1">{{ __('messages.stat_favorites') }}</div>
                </div>
                <div class="otl-surface p-5 text-center">
                    <div class="text-3xl font-extrabold text-[#8ee30f]">{{ $stats['reviews'] }}</div>
                    <div class="text-sm text-[#7e8488] mt-1">{{ __('messages.stat_reviews') }}</div>
                </div>
            </div>

            {{-- Recent bookings --}}
            <div class="otl-surface p-6">
                <h3 class="text-lg font-bold text-white mb-4">{{ __('messages.recent_bookings') }}</h3>
                <div class="space-y-3">
                    @forelse($bookings as $booking)
                        <a href="{{ route('bookings.show', $booking) }}" class="flex justify-between items-center bg-[#141516] rounded-2xl p-4 hover:bg-[#1f2021] transition">
                            <div class="min-w-0">
                                <p class="font-semibold text-white truncate">{{ $booking->room->hotel->name }}</p>
                                <p class="text-sm text-[#7e8488]">{{ $booking->check_in->format('d.m.Y') }} – {{ $booking->check_out->format('d.m.Y') }}</p>
                            </div>
                            <span class="text-[#8ee30f] font-bold flex-shrink-0 ml-3">{{ number_format($booking->total_price, 0, '.', ' ') }} ₸</span>
                        </a>
                    @empty
                        <p class="text-[#7e8488]">{{ __('messages.no_recent_bookings') }}</p>
                    @endforelse
                </div>
                @if($bookings->count())
                    <a href="{{ route('bookings.index') }}" class="mt-4 inline-block text-[#8ee30f] hover:underline">{{ __('messages.all_bookings') }} →</a>
                @endif
            </div>

            {{-- Favorites preview --}}
            @if($favorites->count())
                <div class="otl-surface p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-white">{{ __('messages.favorite_hotels') }}</h3>
                        <a href="{{ route('favorites.index') }}" class="text-sm text-[#8ee30f] hover:underline">{{ __('messages.all') }} →</a>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach($favorites as $favorite)
                            @php($hotel = $favorite->hotel)
                            <a href="{{ route('hotels.show', $hotel->id) }}" class="group block">
                                <div class="aspect-[4/3] rounded-2xl overflow-hidden bg-[#141516] mb-2">
                                    @if($hotel->image)
                                        <img src="{{ $hotel->image_url }}" alt="{{ $hotel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @endif
                                </div>
                                <div class="text-sm text-white font-medium truncate">{{ $hotel->name }}</div>
                                <div class="text-xs text-[#7e8488] truncate">{{ $hotel->city }}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Right --}}
        <div class="space-y-6">
            {{-- Account info --}}
            <div class="otl-surface p-6">
                <h3 class="text-lg font-bold text-white mb-5">{{ __('messages.about_account') }}</h3>
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between gap-3">
                        <span class="text-[#7e8488]">{{ __('messages.email') }}</span>
                        <span class="text-white text-right truncate">{{ $user->email }}</span>
                    </div>
                    <div class="flex justify-between gap-3">
                        <span class="text-[#7e8488]">{{ __('messages.with_us_since') }}</span>
                        <span class="text-white">{{ $user->created_at->format('d.m.Y') }}</span>
                    </div>
                    <div class="flex justify-between gap-3">
                        <span class="text-[#7e8488]">{{ __('messages.status') }}</span>
                        <span class="text-white">{{ $user->isAdmin() ? __('messages.role_admin') : __('messages.role_user') }}</span>
                    </div>
                </div>
                <button onclick="document.getElementById('edit-modal').classList.remove('hidden')" class="btn-dark w-full py-2.5 mt-5">
                    {{ __('messages.edit_profile') }}
                </button>
            </div>

            {{-- Support --}}
            <div class="otl-surface p-6">
                <h3 class="text-lg font-bold text-white mb-5">{{ __('messages.support') }}</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-full bg-[#141516] flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-[#8ee30f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.7 9.7 0 01-4-.85L3 20l1.35-4A7.9 7.9 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </span>
                        <span class="text-gray-200">{{ __('messages.online_chat') }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-full bg-[#141516] flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-[#8ee30f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </span>
                        <span class="text-gray-200">8 800 000-00-00</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-full bg-[#141516] flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-[#8ee30f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </span>
                        <span class="text-gray-200">support@kazakhhotels.kz</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit modal --}}
<div id="edit-modal" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-4">
    <div class="otl-surface p-6 max-w-md w-full max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold text-white mb-5">{{ __('messages.edit_profile') }}</h3>
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.name') }}</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="field-input @error('name') border-[#f04141] @enderror">
                @error('name') <p class="text-xs text-[#ff8a8a] mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.email') }}</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="field-input @error('email') border-[#f04141] @enderror">
                @error('email') <p class="text-xs text-[#ff8a8a] mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 mt-2 border-t border-white/10">
                <h4 class="text-white font-semibold mb-1">{{ __('messages.change_password') }}</h4>
                <p class="text-xs text-[#7e8488] mb-4">{{ __('messages.password_optional_hint') }}</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.current_password') }}</label>
                        <input type="password" name="current_password" autocomplete="current-password" class="field-input @error('current_password') border-[#f04141] @enderror">
                        @error('current_password') <p class="text-xs text-[#ff8a8a] mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.new_password') }}</label>
                        <input type="password" name="password" autocomplete="new-password" class="field-input @error('password') border-[#f04141] @enderror">
                        @error('password') <p class="text-xs text-[#ff8a8a] mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.confirm_password') }}</label>
                        <input type="password" name="password_confirmation" autocomplete="new-password" class="field-input">
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-accent flex-1 py-2.5">{{ __('messages.save') }}</button>
                <button type="button" onclick="document.getElementById('edit-modal').classList.add('hidden')" class="btn-dark px-6 py-2.5">{{ __('messages.cancel') }}</button>
            </div>
        </form>
    </div>
</div>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('edit-modal')?.classList.remove('hidden');
    });
</script>
@endif
@endsection
