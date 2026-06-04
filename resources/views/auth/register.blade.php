@extends('layouts.app')

@section('title', 'Регистрация - Kazakh Hotels')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="otl-surface p-8">
        <h1 class="text-2xl font-extrabold mb-6 text-center text-white">Регистрация</h1>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-[#7e8488] mb-2">Имя</label>
                    <input type="text" name="name" required value="{{ old('name') }}" class="field-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#7e8488] mb-2">Email</label>
                    <input type="email" name="email" required value="{{ old('email') }}" class="field-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#7e8488] mb-2">Пароль</label>
                    <input type="password" name="password" required class="field-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#7e8488] mb-2">Подтвердите пароль</label>
                    <input type="password" name="password_confirmation" required class="field-input">
                </div>
                <button type="submit" class="btn-accent w-full py-3">Зарегистрироваться</button>
            </div>
        </form>

        <p class="mt-6 text-center text-sm text-[#7e8488]">
            Уже есть аккаунт?
            <a href="{{ route('login') }}" class="text-[#8ee30f] hover:underline">Войти</a>
        </p>
    </div>
</div>
@endsection
