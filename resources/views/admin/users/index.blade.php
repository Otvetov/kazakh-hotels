@extends('layouts.app')

@section('title', 'Управление пользователями - Админ')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Управление пользователями</h1>

    <div class="bg-whitebg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200border-gray-700">
        <table class="w-full">
            <thead class="bg-gray-50bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Имя</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Роль</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Статус</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Создан</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500text-gray-300 uppercase">Действия</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200divide-gray-700">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50hover:bg-gray-700">
                        <td class="px-6 py-4 font-semibold">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded {{ $user->isAdmin() ? 'bg-purple-100bg-purple-900 text-purple-800text-purple-200' : 'bg-gray-100bg-gray-700 text-gray-800text-gray-200' }}">
                                {{ $user->isAdmin() ? 'Администратор' : 'Пользователь' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->isBanned())
                                <span class="px-2 py-1 rounded bg-red-100bg-red-900 text-red-800text-red-200">Заблокирован</span>
                            @else
                                <span class="px-2 py-1 rounded bg-green-100bg-green-900 text-green-800text-green-200">Активен</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $user->created_at->format('d.m.Y') }}</td>
                        <td class="px-6 py-4">
                            @if($user->isBanned())
                                <form action="{{ route('admin.users.unban', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-sm">Разблокировать</button>
                                </form>
                            @else
                                <form action="{{ route('admin.users.ban', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Вы уверены?')" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">Заблокировать</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500text-gray-400">Пользователи не найдены</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection


