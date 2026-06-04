@extends('layouts.app')

@section('title', 'API Документация - Kazakh Hotels')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">API Документация</h1>
            <p class="text-gray-600 mb-8">Базовый URL: <code class="bg-gray-100 px-2 py-1 rounded">{{ url('/api/v1') }}</code></p>

            <!-- Public Endpoints -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Публичные эндпоинты</h2>
                
                <div class="space-y-4">
                    <!-- Authentication -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Аутентификация</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm font-semibold">POST</span>
                                    <code class="text-sm">{{ url('/api/v1/register') }}</code>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">Регистрация нового пользователя</p>
                                <details class="text-sm">
                                    <summary class="cursor-pointer text-[#8ee30f] hover:underline">Параметры</summary>
                                    <pre class="mt-2 p-3 bg-gray-50 rounded text-xs overflow-x-auto">{
  "name": "Имя пользователя",
  "email": "email@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}</pre>
                                </details>
                                <a href="{{ url('/api/v1/register') }}" target="_blank" class="text-sm text-[#8ee30f] hover:underline mt-2 inline-block">
                                    Открыть в новой вкладке →
                                </a>
                            </div>

                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm font-semibold">POST</span>
                                    <code class="text-sm">{{ url('/api/v1/login') }}</code>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">Вход в систему</p>
                                <details class="text-sm">
                                    <summary class="cursor-pointer text-[#8ee30f] hover:underline">Параметры</summary>
                                    <pre class="mt-2 p-3 bg-gray-50 rounded text-xs overflow-x-auto">{
  "email": "email@example.com",
  "password": "password123"
}</pre>
                                </details>
                            </div>
                        </div>
                    </div>

                    <!-- Hotels -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Отели</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm font-semibold">GET</span>
                                    <code class="text-sm">{{ url('/api/v1/hotels') }}</code>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">Список отелей</p>
                                <p class="text-xs text-gray-500 mb-2">Параметры: <code>?city=Алматы&search=отель&min_price=1000&max_price=5000&rating=4&sort=rating&per_page=15</code></p>
                                <a href="{{ url('/api/v1/hotels') }}" target="_blank" class="text-sm text-[#8ee30f] hover:underline">
                                    Открыть в браузере →
                                </a>
                            </div>

                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm font-semibold">GET</span>
                                    <code class="text-sm">{{ url('/api/v1/hotels/1') }}</code>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">Детали отеля</p>
                                <a href="{{ url('/api/v1/hotels/1') }}" target="_blank" class="text-sm text-[#8ee30f] hover:underline">
                                    Открыть в браузере →
                                </a>
                            </div>

                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm font-semibold">GET</span>
                                    <code class="text-sm">{{ url('/api/v1/hotels/1/rooms') }}</code>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">Номера отеля</p>
                                <p class="text-xs text-gray-500 mb-2">Параметры: <code>?check_in=2024-01-15&check_out=2024-01-20</code></p>
                                <a href="{{ url('/api/v1/hotels/1/rooms') }}" target="_blank" class="text-sm text-[#8ee30f] hover:underline">
                                    Открыть в браузере →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Protected Endpoints -->
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Защищенные эндпоинты (требуется аутентификация)</h2>
                <p class="text-sm text-gray-600 mb-4">Для доступа к этим эндпоинтам необходимо сначала войти через <code class="bg-gray-100 px-1 rounded">POST /api/v1/login</code></p>
                
                <div class="space-y-4">
                    <!-- User Profile -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Профиль пользователя</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm font-semibold">GET</span>
                                    <code class="text-sm">{{ url('/api/v1/user') }}</code>
                                </div>
                                <p class="text-sm text-gray-600">Получить информацию о текущем пользователе</p>
                            </div>

                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-sm font-semibold">PUT</span>
                                    <code class="text-sm">{{ url('/api/v1/user') }}</code>
                                </div>
                                <p class="text-sm text-gray-600">Обновить профиль пользователя</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bookings -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Бронирования</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm font-semibold">GET</span>
                                    <code class="text-sm">{{ url('/api/v1/bookings') }}</code>
                                </div>
                                <p class="text-sm text-gray-600">Список бронирований пользователя</p>
                            </div>

                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm font-semibold">POST</span>
                                    <code class="text-sm">{{ url('/api/v1/bookings') }}</code>
                                </div>
                                <p class="text-sm text-gray-600">Создать новое бронирование</p>
                            </div>

                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm font-semibold">GET</span>
                                    <code class="text-sm">{{ url('/api/v1/bookings/1') }}</code>
                                </div>
                                <p class="text-sm text-gray-600">Детали бронирования</p>
                            </div>

                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm font-semibold">POST</span>
                                    <code class="text-sm">{{ url('/api/v1/bookings/1/cancel') }}</code>
                                </div>
                                <p class="text-sm text-gray-600">Отменить бронирование</p>
                            </div>
                        </div>
                    </div>

                    <!-- Favorites -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Избранное</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm font-semibold">GET</span>
                                    <code class="text-sm">{{ url('/api/v1/favorites') }}</code>
                                </div>
                                <p class="text-sm text-gray-600">Список избранных отелей</p>
                            </div>

                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm font-semibold">POST</span>
                                    <code class="text-sm">{{ url('/api/v1/favorites/1') }}</code>
                                </div>
                                <p class="text-sm text-gray-600">Добавить/удалить отель из избранного</p>
                            </div>

                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-sm font-semibold">DELETE</span>
                                    <code class="text-sm">{{ url('/api/v1/favorites/1') }}</code>
                                </div>
                                <p class="text-sm text-gray-600">Удалить отель из избранного</p>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Отзывы</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm font-semibold">POST</span>
                                    <code class="text-sm">{{ url('/api/v1/hotels/1/reviews') }}</code>
                                </div>
                                <p class="text-sm text-gray-600">Оставить отзыв об отеле</p>
                            </div>

                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm font-semibold">GET</span>
                                    <code class="text-sm">{{ url('/api/v1/hotels/1/reviews') }}</code>
                                </div>
                                <p class="text-sm text-gray-600">Список отзывов об отеле</p>
                            </div>
                        </div>
                    </div>

                    <!-- Logout -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm font-semibold">POST</span>
                            <code class="text-sm">{{ url('/api/v1/logout') }}</code>
                        </div>
                        <p class="text-sm text-gray-600">Выход из системы</p>
                    </div>
                </div>
            </div>

            <!-- Testing Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Как протестировать API</h3>
                <ul class="list-disc list-inside space-y-2 text-sm text-gray-700">
                    <li><strong>GET-запросы</strong> можно открыть прямо в браузере (например, <a href="{{ url('/api/v1/hotels') }}" target="_blank" class="text-[#8ee30f] hover:underline">{{ url('/api/v1/hotels') }}</a>)</li>
                    <li><strong>POST/PUT/DELETE-запросы</strong> требуют использования инструментов типа Postman, Insomnia или расширений браузера (например, REST Client для VS Code)</li>
                    <li>Для <strong>защищенных эндпоинтов</strong> необходимо сначала войти через <code class="bg-white px-1 rounded">POST /api/v1/login</code></li>
                    <li>Laravel использует сессии для аутентификации, поэтому после входа через API вы будете авторизованы в браузере</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection


