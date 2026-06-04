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

            {{-- Recent bookings --}}
            <div class="otl-surface p-6">
                <h3 class="text-lg font-bold text-white mb-4">Последние бронирования</h3>
                <div class="space-y-3">
                    @forelse($bookings as $booking)
                        <div class="flex justify-between items-start bg-[#141516] rounded-2xl p-4">
                            <div class="min-w-0">
                                <p class="font-semibold text-white truncate">{{ $booking->room->hotel->name }}</p>
                                <p class="text-sm text-[#7e8488]">{{ $booking->check_in->format('d.m.Y') }} – {{ $booking->check_out->format('d.m.Y') }}</p>
                            </div>
                            <span class="text-[#8ee30f] font-bold flex-shrink-0 ml-3">{{ number_format($booking->total_price, 0, '.', ' ') }} ₸</span>
                        </div>
                    @empty
                        <p class="text-[#7e8488]">Нет последних бронирований</p>
                    @endforelse
                </div>
                <a href="{{ route('bookings.index') }}" class="mt-4 inline-block text-[#8ee30f] hover:underline">
                    Все бронирования →
                </a>
            </div>
        </div>

        {{-- Right: support --}}
        <div>
            <div class="otl-surface p-6">
                <h3 class="text-lg font-bold text-white mb-5">Поддержка</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-full bg-[#141516] flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-[#8ee30f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z M3 12a9 9 0 019-9"></path>
                            </svg>
                        </span>
                        <span class="text-gray-200">Онлайн-чат</span>
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
    <div class="otl-surface p-6 max-w-md w-full">
        <h3 class="text-xl font-bold text-white mb-4">Редактировать профиль</h3>
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-[#7e8488] mb-2">Имя</label>
                <input type="text" name="name" value="{{ $user->name }}" required class="field-input">
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-accent flex-1 py-2.5">Сохранить</button>
                <button type="button" onclick="document.getElementById('edit-modal').classList.add('hidden')" class="btn-dark px-6 py-2.5">Отмена</button>
            </div>
        </form>
    </div>
</div>
@endsection
