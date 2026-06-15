@extends('layouts.app')

@section('title', 'Регистрация - Kazakh Hotels')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="otl-surface p-8">
        <h1 class="text-2xl font-extrabold mb-6 text-center text-white">{{ __('messages.register_title') }}</h1>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.name') }}</label>
                    <input type="text" name="name" required value="{{ old('name') }}" class="field-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.email') }}</label>
                    <input type="email" name="email" required value="{{ old('email') }}" class="field-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.password') }}</label>
                    <input type="password" name="password" required class="field-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#7e8488] mb-2">{{ __('messages.confirm_password') }}</label>
                    <input type="password" name="password_confirmation" required class="field-input">
                </div>
                <button type="submit" class="btn-accent w-full py-3">{{ __('messages.register') }}</button>
            </div>
        </form>

        <p class="mt-6 text-center text-sm text-[#7e8488]">
            {{ __('messages.have_account') }}
            <a href="{{ route('login') }}" class="text-[#8ee30f] hover:underline">{{ __('messages.login') }}</a>
        </p>
    </div>
</div>
@endsection
