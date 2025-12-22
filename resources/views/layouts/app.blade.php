<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kazakh Hotels')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|nunito:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-sans antialiased">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-[#38b000]">Kazakh Hotels</a>
                        <div class="hidden md:flex space-x-6">
                            <a href="{{ route('home') }}" class="hover:text-[#38b000] transition">Home</a>
                            <a href="{{ route('hotels.index') }}" class="hover:text-[#38b000] transition">Hotels</a>
                            @auth
                                <a href="{{ route('favorites.index') }}" class="hover:text-[#38b000] transition">Favorites</a>
                                <a href="{{ route('bookings.index') }}" class="hover:text-[#38b000] transition">Bookings</a>
                            @endauth
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <div class="relative group">
                                <button class="flex items-center space-x-2 focus:outline-none">
                                    <div class="w-8 h-8 rounded-full bg-[#38b000] flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <span class="hidden md:block">{{ auth()->user()->name }}</span>
                                </button>
                                <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Profile</a>
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.index') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Admin Panel</a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Logout</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-[#38b000] transition">Login</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8a00] transition">Register</a>
                        @endauth
                    </div>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main>
            @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded">
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
        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <p class="text-center text-gray-600 dark:text-gray-400">&copy; {{ date('Y') }} Kazakh Hotels. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>

