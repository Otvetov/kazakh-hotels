@extends('layouts.app')

@section('title', 'Вход - Kazakh Hotels')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="otl-surface p-8">
        <h1 class="text-2xl font-extrabold mb-6 text-center text-white">Вход</h1>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-[#7e8488] mb-2">Email</label>
                    <input type="email" name="email" required value="{{ old('email') }}" class="field-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#7e8488] mb-2">Пароль</label>
                    <input type="password" name="password" required class="field-input">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded bg-[#141516] border-white/20 accent-[#8ee30f]">
                    <label for="remember" class="ml-2 text-sm text-gray-300">Запомнить меня</label>
                </div>
                <button type="submit" class="btn-accent w-full py-3">Войти</button>
            </div>
        </form>

        <p class="mt-6 text-center text-sm text-[#7e8488]">
            Нет аккаунта?
            <a href="{{ route('register') }}" class="text-[#8ee30f] hover:underline">Зарегистрироваться</a>
        </p>
    </div>
</div>
@endsection
