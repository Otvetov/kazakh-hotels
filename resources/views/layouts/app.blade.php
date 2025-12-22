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
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-[#0066cc]">Kazakh Hotels</a>
                        <div class="hidden md:flex space-x-6">
                            <a href="{{ route('home') }}" class="text-gray-700 hover:text-[#0066cc] transition py-2">Главная</a>
                            <a href="{{ route('hotels.index') }}" class="text-gray-700 hover:text-[#0066cc] transition py-2">Отели</a>
                            @auth
                                <a href="{{ route('favorites.index') }}" class="text-gray-700 hover:text-[#0066cc] transition py-2">Избранное</a>
                                <a href="{{ route('bookings.index') }}" class="text-gray-700 hover:text-[#0066cc] transition py-2">Брони</a>
                            @endauth
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <div class="relative group">
                                <button class="flex items-center space-x-2 focus:outline-none py-2">
                                    <div class="w-8 h-8 rounded-full bg-[#0066cc] flex items-center justify-center text-white font-semibold text-sm">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <span class="hidden md:block text-gray-700">{{ auth()->user()->name }}</span>
                                </button>
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50 border border-gray-200">
                                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 hover:bg-gray-50 text-gray-700">Профиль</a>
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.index') }}" class="block px-4 py-2 hover:bg-gray-50 text-gray-700">Админ панель</a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-50 text-gray-700">Выйти</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-[#0066cc] transition py-2">Войти</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-[#0066cc] text-white rounded-md hover:bg-[#0052a3] transition">Регистрация</a>
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
                            <li><a href="#" class="hover:text-[#0066cc]">Поддержка</a></li>
                            <li><a href="{{ route('hotels.index') }}" class="hover:text-[#0066cc]">Все отели</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">О проекте</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li><a href="#" class="hover:text-[#0066cc]">О нас</a></li>
                            <li><a href="#" class="hover:text-[#0066cc]">Контакты</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">Правовая информация</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li><a href="#" class="hover:text-[#0066cc]">Политика конфиденциальности</a></li>
                            <li><a href="#" class="hover:text-[#0066cc]">Условия использования</a></li>
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
