<!DOCTYPE html>
@php $theme = request()->cookie('theme') === 'dark' ? 'theme-dark' : 'theme-light'; @endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $theme }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kazakh Hotels - Отели для путешествий по Казахстану')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black text-[#fafafa] font-sans antialiased">
    <div class="min-h-screen flex flex-col">

        <header class="bg-black/95 backdrop-blur border-b border-white/5 sticky top-0 z-50">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('home') }}" class="flex items-center gap-2">
                            <div class="bg-[#8ee30f] p-2 rounded-xl">
                                <svg class="w-6 h-6 text-[#0a0a0a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                            <span class="text-xl font-extrabold text-white">Kazakh Hotels</span>
                        </a>
                        <div class="hidden md:flex space-x-6">
                            <a href="{{ route('home') }}" class="text-gray-300 hover:text-[#8ee30f] transition py-2">{{ __('messages.nav_home') }}</a>
                            <a href="{{ route('hotels.index') }}" class="text-gray-300 hover:text-[#8ee30f] transition py-2">{{ __('messages.nav_hotels') }}</a>
                            @auth
                                <a href="{{ route('favorites.index') }}" class="text-gray-300 hover:text-[#8ee30f] transition py-2">{{ __('messages.nav_favorites') }}</a>
                                <a href="{{ route('bookings.index') }}" class="text-gray-300 hover:text-[#8ee30f] transition py-2">{{ __('messages.nav_bookings') }}</a>
                            @endauth
                            @if(auth()->check() && auth()->user()->isAdmin())
                                <a href="{{ route('admin.index') }}" class="text-gray-300 hover:text-[#8ee30f] transition py-2">{{ __('messages.nav_admin') }}</a>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        {{-- Переключатель темы --}}
                        <button type="button" onclick="toggleTheme()" class="p-2 rounded-lg text-gray-300 hover:text-[#8ee30f] transition" aria-label="Сменить тему">
                            <svg class="icon-moon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                            <svg class="icon-sun w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </button>

                        {{-- Переключатель языка --}}
                        @php
                            $localeCodes = ['ru' => 'RU', 'en' => 'EN', 'kk' => 'KZ'];
                            $localeNames = ['ru' => __('messages.lang_ru'), 'en' => __('messages.lang_en'), 'kk' => __('messages.lang_kk')];
                            $currentLocale = app()->getLocale();
                        @endphp
                        <div class="relative" id="lang-switcher">
                            <button type="button" onclick="toggleLangMenu(event)"
                                    class="flex items-center gap-1.5 px-3 py-2 text-gray-300 hover:text-[#8ee30f] transition rounded-lg"
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
                            <div id="lang-menu" class="hidden absolute right-0 mt-2 w-40 bg-[#1b1c1d] border border-white/10 rounded-2xl shadow-xl py-1 z-50">
                                @foreach($localeNames as $code => $label)
                                    <a href="{{ route('locale.switch', $code) }}"
                                       class="block px-4 py-2 text-sm hover:bg-white/5 transition {{ $currentLocale === $code ? 'text-[#8ee30f] font-semibold' : 'text-gray-300' }}">
                                        {{ $label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        @auth
                            <div class="flex items-center gap-3">
                                <a href="{{ route('profile.show') }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                                    <div class="w-9 h-9 bg-[#8ee30f] rounded-full flex items-center justify-center text-[#0a0a0a] font-semibold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <span class="hidden md:block text-gray-200">{{ auth()->user()->name }}</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 text-gray-400 hover:text-[#8ee30f] transition">{{ __('messages.logout') }}</button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-gray-300 hover:text-[#8ee30f] transition">{{ __('messages.login') }}</a>
                            <a href="{{ route('register') }}" class="px-5 py-2 bg-[#8ee30f] text-[#0a0a0a] font-semibold rounded-full hover:bg-[#76c406] transition">{{ __('messages.register') }}</a>
                        @endauth
                    </div>
                </div>
            </nav>
        </header>

        <main class="flex-1">
            @yield('content')
        </main>

        <footer class="mt-16 px-4 sm:px-6 lg:px-8 pb-8">
            <div class="max-w-7xl mx-auto otl-surface-green p-8 md:p-10">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="font-semibold text-white mb-3">{{ __('messages.footer_customers') }}</h3>
                        <ul class="space-y-2 text-sm text-gray-300">
                            <li><a href="#" class="hover:text-[#8ee30f] transition">{{ __('messages.footer_support') }}</a></li>
                            <li><a href="{{ route('hotels.index') }}" class="hover:text-[#8ee30f] transition">{{ __('messages.footer_all_hotels') }}</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-white mb-3">{{ __('messages.footer_about_project') }}</h3>
                        <ul class="space-y-2 text-sm text-gray-300">
                            <li><a href="#" class="hover:text-[#8ee30f] transition">{{ __('messages.footer_about_us') }}</a></li>
                            <li><a href="#" class="hover:text-[#8ee30f] transition">{{ __('messages.footer_contacts') }}</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-white mb-3">{{ __('messages.footer_legal') }}</h3>
                        <ul class="space-y-2 text-sm text-gray-300">
                            <li><a href="#" class="hover:text-[#8ee30f] transition">{{ __('messages.footer_privacy') }}</a></li>
                            <li><a href="#" class="hover:text-[#8ee30f] transition">{{ __('messages.footer_terms') }}</a></li>
                        </ul>
                    </div>
                    <div class="flex md:justify-end items-start">
                        <span class="text-3xl font-extrabold text-[#8ee30f]">Kazakh Hotels</span>
                    </div>
                </div>
                <div class="pt-6 mt-6 border-t border-white/10 text-sm text-gray-400">
                    <p>&copy; {{ date('Y') }} Kazakh Hotels. {{ __('messages.footer_rights') }}</p>
                </div>
            </div>
        </footer>
    </div>

    {{-- Контейнер всплывающих уведомлений --}}
    <div id="toast-container" class="fixed top-20 right-4 z-[100] flex flex-col gap-2 w-[calc(100%-2rem)] max-w-sm pointer-events-none"></div>

    {{-- Плавающий чат ИИ-помощника --}}
    @include('partials.ai-chat')

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.classList.contains('theme-dark');
            const next = isDark ? 'light' : 'dark';
            html.classList.remove('theme-dark', 'theme-light');
            html.classList.add('theme-' + next);
            document.cookie = 'theme=' + next + '; path=/; max-age=31536000; SameSite=Lax';
        }

        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const colors = {
                info:    'bg-[#1b1c1d] border-white/10 text-gray-100',
                success: 'bg-[#1b1c1d] border-[#8ee30f]/40 text-[#8ee30f]',
                error:   'bg-[#1b1c1d] border-[#f04141]/40 text-[#ff8a8a]',
            };
            const toast = document.createElement('div');
            toast.className = 'pointer-events-auto border rounded-2xl px-4 py-3 shadow-xl text-sm transition-all duration-300 opacity-0 translate-y-[-8px] ' + (colors[type] || colors.info);
            toast.textContent = message;
            container.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.remove('opacity-0', 'translate-y-[-8px]');
            });

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-[-8px]');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

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

        // Добавление/удаление из избранного без перезагрузки страницы
        @auth
        const FAV_T = { added: @json(__('messages.fav_added')), removed: @json(__('messages.fav_removed')) };

        function toggleFavorite(hotelId, btn) {
            fetch(`/favorite/${hotelId}/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            })
            .then(r => r.json())
            .then(data => {
                if (btn) {
                    const svg = btn.querySelector('svg');
                    const inactive = btn.dataset.inactive || 'text-white';
                    if (data.is_favorited) {
                        svg.classList.remove(inactive);
                        svg.classList.add('fill-current', 'text-[#f04141]');
                    } else {
                        svg.classList.remove('fill-current', 'text-[#f04141]');
                        svg.classList.add(inactive);
                    }
                }
                if (typeof showToast === 'function') {
                    showToast(data.is_favorited ? FAV_T.added : FAV_T.removed, 'success');
                }
            })
            .catch(() => console.error('favorite toggle failed'));
        }
        @endauth

        // Flash-сообщения показываем как уведомления справа сверху
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success')) showToast(@json(session('success')), 'success'); @endif
            @if(session('info')) showToast(@json(session('info')), 'info'); @endif
            @if($errors->any())
                @foreach($errors->all() as $error)
                    showToast(@json($error), 'error');
                @endforeach
            @endif
        });
    </script>
</body>
</html>
