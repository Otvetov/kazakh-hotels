@extends('layouts.app')

@section('title', __('messages.pay_title') . ' - Kazakh Hotels')

@section('content')
@php
    $inputCls = 'w-full px-4 py-3 bg-[#141516] border border-white/10 rounded-2xl focus:outline-none focus:border-[#8ee30f] text-white placeholder-[#7e8488]';
@endphp
<div class="max-w-5xl mx-auto px-4 py-8">

    <a href="{{ route('hotels.show', $booking->room->hotel) }}" class="inline-flex items-center gap-2 text-[#7e8488] hover:text-[#8ee30f] transition mb-6">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        {{ __('messages.back') }}
    </a>

    <h1 class="text-white text-2xl font-extrabold mb-1">{{ __('messages.pay_title') }}</h1>
    <p class="text-[#7e8488] mb-6">{{ __('messages.pay_subtitle') }}</p>

    <form action="{{ route('bookings.pay', $booking) }}" method="POST" id="paymentForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-6 items-start">

            {{-- Способы оплаты --}}
            <div class="space-y-4">
                <div class="space-y-3">
                    {{-- Карта --}}
                    <label class="pay-method flex items-center gap-4 p-4 otl-surface cursor-pointer border border-white/10 has-[:checked]:border-[#8ee30f] transition">
                        <input type="radio" name="payment_method" value="card" class="accent-[#8ee30f] w-5 h-5" checked>
                        <div class="w-10 h-10 rounded-xl bg-[#8ee30f]/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#8ee30f]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h2m4 0h4M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-white font-semibold">{{ __('messages.pay_method_card') }}</div>
                            <div class="text-sm text-[#7e8488]">{{ __('messages.pay_method_card_desc') }}</div>
                        </div>
                    </label>

                    {{-- Kaspi --}}
                    <label class="pay-method flex items-center gap-4 p-4 otl-surface cursor-pointer border border-white/10 has-[:checked]:border-[#8ee30f] transition">
                        <input type="radio" name="payment_method" value="kaspi" class="accent-[#8ee30f] w-5 h-5">
                        <div class="w-10 h-10 rounded-xl bg-[#f14635]/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#f14635]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2"></path></svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-white font-semibold">{{ __('messages.pay_method_kaspi') }}</div>
                            <div class="text-sm text-[#7e8488]">{{ __('messages.pay_method_kaspi_desc') }}</div>
                        </div>
                    </label>

                    {{-- Наличными при заселении --}}
                    <label class="pay-method flex items-center gap-4 p-4 otl-surface cursor-pointer border border-white/10 has-[:checked]:border-[#8ee30f] transition">
                        <input type="radio" name="payment_method" value="cash" class="accent-[#8ee30f] w-5 h-5">
                        <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-white font-semibold">{{ __('messages.pay_method_cash') }}</div>
                            <div class="text-sm text-[#7e8488]">{{ __('messages.pay_method_cash_desc') }}</div>
                        </div>
                    </label>
                </div>

                {{-- Поля карты (демо: не отправляются на сервер, без атрибута name) --}}
                <div id="cardFields" class="otl-surface p-5 space-y-4">
                    <div>
                        <label class="block text-sm text-[#7e8488] mb-2">{{ __('messages.pay_card_number') }}</label>
                        <input type="text" id="cardNumber" inputmode="numeric" autocomplete="off" maxlength="19"
                               placeholder="0000 0000 0000 0000" class="{{ $inputCls }} tracking-widest">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-[#7e8488] mb-2">{{ __('messages.pay_card_expiry') }}</label>
                            <input type="text" id="cardExpiry" inputmode="numeric" autocomplete="off" maxlength="5"
                                   placeholder="ММ/ГГ" class="{{ $inputCls }}">
                        </div>
                        <div>
                            <label class="block text-sm text-[#7e8488] mb-2">{{ __('messages.pay_card_cvv') }}</label>
                            <input type="text" id="cardCvv" inputmode="numeric" autocomplete="off" maxlength="4"
                                   placeholder="123" class="{{ $inputCls }}">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm text-[#7e8488] mb-2">{{ __('messages.pay_card_holder') }}</label>
                        <input type="text" id="cardHolder" autocomplete="off" placeholder="IVAN IVANOV"
                               class="{{ $inputCls }} uppercase">
                    </div>
                </div>

                {{-- Подсказки для других способов --}}
                <div id="kaspiNote" class="hidden otl-surface p-5 text-sm text-gray-300">{{ __('messages.pay_kaspi_note') }}</div>
                <div id="cashNote" class="hidden otl-surface p-5 text-sm text-gray-300">{{ __('messages.pay_cash_note') }}</div>

                <p class="flex items-start gap-2 text-xs text-[#7e8488]">
                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-[#8ee30f]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <span>{{ __('messages.pay_demo_note') }}</span>
                </p>
            </div>

            {{-- Сводка заказа --}}
            <div class="otl-surface p-6 lg:sticky lg:top-24">
                <h2 class="text-white font-bold mb-4">{{ __('messages.pay_order') }}</h2>
                <div class="space-y-2.5 text-sm">
                    <div class="flex justify-between gap-4">
                        <span class="text-[#7e8488]">{{ __('messages.hotel') }}</span>
                        <span class="text-white font-medium text-right">{{ $booking->room->hotel->name }}</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-[#7e8488]">{{ __('messages.room') }}</span>
                        <span class="text-white font-medium text-right">{{ $booking->room->name }}</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-[#7e8488]">{{ __('messages.dates') }}</span>
                        <span class="text-white font-medium text-right">{{ $booking->check_in->format('d.m.Y') }} – {{ $booking->check_out->format('d.m.Y') }}</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-[#7e8488]">{{ __('messages.guests_label') }}</span>
                        <span class="text-white font-medium">{{ $booking->guests }}</span>
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="text-[#7e8488]">{{ __('messages.nights_label') }}</span>
                        <span class="text-white font-medium">{{ $nights }}</span>
                    </div>
                </div>
                <div class="flex justify-between items-center gap-4 pt-4 mt-4 border-t border-white/10">
                    <span class="text-white font-semibold">{{ __('messages.total_to_pay') }}</span>
                    <span class="text-[#8ee30f] font-bold text-xl">{{ number_format($booking->total_price, 0, '.', ' ') }} ₸</span>
                </div>

                <button type="submit" class="btn-accent w-full py-3.5 mt-6">
                    {{ __('messages.pay_button') }} {{ number_format($booking->total_price, 0, '.', ' ') }} ₸
                </button>
            </div>
        </div>
    </form>
</div>

<script>
(function () {
    const cardFields = document.getElementById('cardFields');
    const kaspiNote = document.getElementById('kaspiNote');
    const cashNote = document.getElementById('cashNote');

    function refresh() {
        const method = document.querySelector('input[name="payment_method"]:checked').value;
        cardFields.classList.toggle('hidden', method !== 'card');
        kaspiNote.classList.toggle('hidden', method !== 'kaspi');
        cashNote.classList.toggle('hidden', method !== 'cash');
    }

    document.querySelectorAll('input[name="payment_method"]').forEach(r => r.addEventListener('change', refresh));

    // Форматирование номера карты по 4 цифры
    const num = document.getElementById('cardNumber');
    num.addEventListener('input', function () {
        let v = this.value.replace(/\D/g, '').slice(0, 16);
        this.value = v.replace(/(.{4})/g, '$1 ').trim();
    });

    // Срок действия ММ/ГГ
    const exp = document.getElementById('cardExpiry');
    exp.addEventListener('input', function () {
        let v = this.value.replace(/\D/g, '').slice(0, 4);
        this.value = v.length > 2 ? v.slice(0, 2) + '/' + v.slice(2) : v;
    });

    // CVV — только цифры
    document.getElementById('cardCvv').addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 4);
    });

    refresh();
})();
</script>
@endsection
