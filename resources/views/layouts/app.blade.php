<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kazakh Hotels - Отели для путешествий по Казахстану')</title>

    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-900 font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        
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
                            <a href="{{ route('home') }}" class="text-gray-700 hover:text-[#38b000] transition py-2">{{ __('messages.nav_home') }}</a>
                            <a href="{{ route('hotels.index') }}" class="text-gray-700 hover:text-[#38b000] transition py-2">{{ __('messages.nav_hotels') }}</a>
                            @auth
                                <a href="{{ route('favorites.index') }}" class="text-gray-700 hover:text-[#38b000] transition py-2">{{ __('messages.nav_favorites') }}</a>
                                <a href="{{ route('bookings.index') }}" class="text-gray-700 hover:text-[#38b000] transition py-2">{{ __('messages.nav_bookings') }}</a>
                            @endauth
                            @if(auth()->check() && auth()->user()->isAdmin())
                                <a href="{{ route('admin.index') }}" class="text-gray-700 hover:text-[#38b000] transition py-2">{{ __('messages.nav_admin') }}</a>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        {{-- Переключатель языка --}}
                        @php
                            $localeCodes = ['ru' => 'RU', 'en' => 'EN', 'kk' => 'KZ'];
                            $localeNames = ['ru' => __('messages.lang_ru'), 'en' => __('messages.lang_en'), 'kk' => __('messages.lang_kk')];
                            $currentLocale = app()->getLocale();
                        @endphp
                        <div class="relative" id="lang-switcher">
                            <button type="button" onclick="toggleLangMenu(event)"
                                    class="flex items-center gap-1.5 px-3 py-2 text-gray-700 hover:text-[#38b000] transition rounded-lg"
                                    aria-haspopup="true" aria-label="{{ __('messages.language') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 010 18M12 3a15 15 0 000 18M12 3a9 9 0 100 18 9 9 0 000-18z"></path>
                                </svg>
                                <span class="text-sm font-medium">{{ $localeCodes[$currentLocale] ?? 'RU' }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div id="lang-menu" class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg py-1 z-50">
                                @foreach($localeNames as $code => $label)
                                    <a href="{{ route('locale.switch', $code) }}"
                                       class="block px-4 py-2 text-sm hover:bg-gray-50 transition {{ $currentLocale === $code ? 'text-[#38b000] font-semibold' : 'text-gray-700' }}">
                                        {{ $label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

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
                                    <button type="submit" class="px-4 py-2 text-gray-700 hover:text-[#38b000] transition">{{ __('messages.logout') }}</button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-2 text-gray-700 hover:text-[#38b000] transition">{{ __('messages.login') }}</a>
                            <a href="{{ route('register') }}" class="px-6 py-2 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8c00] transition">{{ __('messages.register') }}</a>
                        @endauth
                    </div>
                </div>
            </nav>
        </header>

        
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

       
        <footer class="bg-gray-50 border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-6">
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">{{ __('messages.footer_customers') }}</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li><a href="#" class="hover:text-[#38b000]">{{ __('messages.footer_support') }}</a></li>
                            <li><a href="{{ route('hotels.index') }}" class="hover:text-[#38b000]">{{ __('messages.footer_all_hotels') }}</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">{{ __('messages.footer_about_project') }}</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li><a href="#" class="hover:text-[#38b000]">{{ __('messages.footer_about_us') }}</a></li>
                            <li><a href="#" class="hover:text-[#38b000]">{{ __('messages.footer_contacts') }}</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-3">{{ __('messages.footer_legal') }}</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li><a href="#" class="hover:text-[#38b000]">{{ __('messages.footer_privacy') }}</a></li>
                            <li><a href="#" class="hover:text-[#38b000]">{{ __('messages.footer_terms') }}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="pt-6 border-t border-gray-200 text-center text-sm text-gray-600">
                    <p>&copy; {{ date('Y') }} Kazakh Hotels. {{ __('messages.footer_rights') }}</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        function toggleLangMenu(event) {
            event.stopPropagation();
            document.getElementById('lang-menu').classList.toggle('hidden');
        }

        document.addEventListener('click', function (event) {
            const switcher = document.getElementById('lang-switcher');
            const menu = document.getElementById('lang-menu');
            if (switcher && menu && !switcher.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
