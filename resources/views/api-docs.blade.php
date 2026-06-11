@extends('layouts.app')

@section('title', 'API Документация - Kazakh Hotels')

@section('content')
@php
    $badge = function ($m) {
        return match ($m) {
            'GET'    => 'background:#3b82f6;color:#fff',
            'POST'   => 'background:#8ee30f;color:#0a0a0a',
            'PUT'    => 'background:#f59e0b;color:#0a0a0a',
            'DELETE' => 'background:#ef4444;color:#fff',
            default  => 'background:#6b7280;color:#fff',
        };
    };

    $public = [
        'Аутентификация' => [
            ['POST', '/api/v1/register', 'Регистрация — в ответе возвращается токен'],
            ['POST', '/api/v1/login', 'Вход — в ответе возвращается токен'],
        ],
        'Отели' => [
            ['GET', '/api/v1/hotels', 'Список отелей (фильтры: city, search, min_price, max_price, rating, sort, per_page)'],
            ['GET', '/api/v1/hotels/{id}', 'Детали отеля'],
            ['GET', '/api/v1/hotels/{id}/rooms', 'Номера отеля (check_in, check_out)'],
        ],
    ];

    $protected = [
        'Профиль' => [
            ['GET', '/api/v1/user', 'Текущий пользователь'],
            ['PUT', '/api/v1/user', 'Обновить профиль'],
        ],
        'Бронирования' => [
            ['GET', '/api/v1/bookings', 'Список бронирований'],
            ['POST', '/api/v1/bookings', 'Создать бронирование'],
            ['GET', '/api/v1/bookings/{id}', 'Детали бронирования'],
            ['POST', '/api/v1/bookings/{id}/cancel', 'Отменить бронирование'],
        ],
        'Избранное' => [
            ['GET', '/api/v1/favorites', 'Список избранного'],
            ['POST', '/api/v1/favorites/{hotel}', 'Добавить/убрать из избранного'],
            ['DELETE', '/api/v1/favorites/{hotel}', 'Удалить из избранного'],
        ],
        'Отзывы' => [
            ['POST', '/api/v1/hotels/{id}/reviews', 'Оставить отзыв'],
            ['GET', '/api/v1/hotels/{id}/reviews', 'Список отзывов'],
        ],
        'Сессия' => [
            ['POST', '/api/v1/logout', 'Выход — отзывает текущий токен'],
        ],
    ];
@endphp

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="otl-surface p-6 md:p-8 mb-6">
        <h1 class="text-2xl md:text-3xl font-extrabold text-white mb-2">API документация</h1>
        <p class="text-[#7e8488]">Базовый URL: <code class="bg-[#141516] px-2 py-1 rounded text-[#8ee30f]">{{ url('/api/v1') }}</code></p>
    </div>

    {{-- Authorization --}}
    <div class="otl-surface p-6 md:p-8 mb-6">
        <h2 class="text-xl font-bold text-white mb-3">Авторизация</h2>
        <p class="text-sm text-gray-300 mb-3">
            API использует токены (Laravel Sanctum). Получите токен через <code class="bg-[#141516] px-1.5 py-0.5 rounded text-[#8ee30f]">POST /api/v1/login</code>
            или <code class="bg-[#141516] px-1.5 py-0.5 rounded text-[#8ee30f]">/register</code> и передавайте его в заголовке каждого защищённого запроса:
        </p>
        <pre class="p-3 bg-[#141516] rounded-xl text-xs text-gray-200 overflow-x-auto">Authorization: Bearer {ваш_токен}
Accept: application/json</pre>
    </div>

    {{-- Public --}}
    <h2 class="text-xl font-extrabold text-white mb-4">Публичные эндпоинты</h2>
    <div class="space-y-4 mb-8">
        @foreach($public as $group => $rows)
            <div class="otl-surface p-5">
                <h3 class="text-lg font-semibold text-white mb-3">{{ $group }}</h3>
                <div class="space-y-3">
                    @foreach($rows as [$method, $path, $desc])
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2 py-0.5 rounded text-xs font-bold" style="{{ $badge($method) }}">{{ $method }}</span>
                                <code class="text-sm text-gray-200">{{ $path }}</code>
                            </div>
                            <p class="text-sm text-[#7e8488] mt-1">{{ $desc }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- Protected --}}
    <h2 class="text-xl font-extrabold text-white mb-2">Защищённые эндпоинты</h2>
    <p class="text-sm text-[#7e8488] mb-4">Требуют заголовок <code class="bg-[#141516] px-1.5 py-0.5 rounded text-[#8ee30f]">Authorization: Bearer …</code></p>
    <div class="space-y-4 mb-8">
        @foreach($protected as $group => $rows)
            <div class="otl-surface p-5">
                <h3 class="text-lg font-semibold text-white mb-3">{{ $group }}</h3>
                <div class="space-y-3">
                    @foreach($rows as [$method, $path, $desc])
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2 py-0.5 rounded text-xs font-bold" style="{{ $badge($method) }}">{{ $method }}</span>
                                <code class="text-sm text-gray-200">{{ $path }}</code>
                            </div>
                            <p class="text-sm text-[#7e8488] mt-1">{{ $desc }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- How to test --}}
    <div class="otl-surface p-6">
        <h3 class="text-lg font-bold text-white mb-3">Как протестировать</h3>
        <ul class="list-disc list-inside space-y-2 text-sm text-gray-300">
            <li>Публичные <strong>GET</strong>-запросы можно открыть прямо в браузере: <a href="{{ url('/api/v1/hotels') }}" target="_blank" class="text-[#8ee30f] hover:underline">{{ url('/api/v1/hotels') }}</a></li>
            <li>Для остальных используйте Postman / Insomnia / curl с заголовком <code class="bg-[#141516] px-1 rounded text-[#8ee30f]">Accept: application/json</code></li>
            <li>Сначала выполните <code class="bg-[#141516] px-1 rounded text-[#8ee30f]">POST /api/v1/login</code>, скопируйте <code class="bg-[#141516] px-1 rounded">token</code> из ответа и добавляйте его как <code class="bg-[#141516] px-1 rounded text-[#8ee30f]">Authorization: Bearer …</code></li>
        </ul>
    </div>
</div>
@endsection
