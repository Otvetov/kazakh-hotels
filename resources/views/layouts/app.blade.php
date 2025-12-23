<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kazakh Hotels - Отели для путешествий по Казахстану')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-900 font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('home') }}" class="flex items-center gap-2">
                            <div class="bg-[#38b000] p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-gray-900">Kazakh Hotels</span>
                        </a>
                        <div class="hidden md:flex space-x-6">
                            <a href="{{ route('home') }}" class="text-gray-700 hover:text-[#38b000] transition py-2">Главная</a>
                            <a href="{{ route('hotels.index') }}" class="text-gray-700 hover:text-[#38b000] transition py-2">Отели</a>
                            @auth
                                <a href="{{ route('favorites.index') }}" class="text-gray-700 hover:text-[#38b000] transition py-2">Избранное</a>
                                <a href="{{ route('bookings.index') }}" class="text-gray-700 hover:text-[#38b000] transition py-2">Бронирования</a>
                            @endauth
                            @if(auth()->check() && auth()->user()->isAdmin())
                                <a href="{{ route('admin.index') }}" class="text-gray-700 hover:text-[#38b000] transition py-2">Админ-панель</a>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <div class="flex items-center gap-3">
                                <a href="{{ route('profile.show') }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                                    <div class="w-9 h-9 bg-[#38b000] rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <span class="hidden md:block text-gray-700">{{ auth()->user()->name }}</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 text-gray-700 hover:text-[#38b000] transition">Выход</button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-2 text-gray-700 hover:text-[#38b000] transition">Войти</a>
                            <a href="{{ route('register') }}" class="px-6 py-2 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8c00] transition">Зарегистрироваться</a>
                        @endauth
                    </div>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="flex-1">
            @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-50 border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-6">
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">Клиентам</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li><a href="#" class="hover:text-[#38b000]">Поддержка</a></li>
                            <li><a href="{{ route('hotels.index') }}" class="hover:text-[#38b000]">Все отели</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">О проекте</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li><a href="#" class="hover:text-[#38b000]">О нас</a></li>
                            <li><a href="#" class="hover:text-[#38b000]">Контакты</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">Правовая информация</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li><a href="#" class="hover:text-[#38b000]">Политика конфиденциальности</a></li>
                            <li><a href="#" class="hover:text-[#38b000]">Условия использования</a></li>
                        </ul>
                    </div>
                </div>
                <div class="pt-6 border-t border-gray-200 text-center text-sm text-gray-600">
                    <p>&copy; {{ date('Y') }} Kazakh Hotels. Все права защищены.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
