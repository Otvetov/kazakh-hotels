@extends('layouts.app')

@section('title', __('messages.admin_manage_users') . ' - Kazakh Hotels')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-extrabold text-white mb-6">{{ __('messages.admin_manage_users') }}</h1>

    <div class="otl-surface overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#141516] text-[#7e8488]">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.admin_name_col') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.email') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.admin_role') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.status') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.admin_created') }}</th>
                        <th class="px-6 py-3 text-left font-medium uppercase text-xs">{{ __('messages.admin_actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($users as $user)
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-6 py-4 font-semibold text-white">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-gray-300">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs {{ $user->isAdmin() ? 'bg-[#8ee30f]/15 text-[#8ee30f]' : 'bg-[#2a2b2c] text-gray-300' }}">
                                    {{ $user->isAdmin() ? __('messages.role_admin') : __('messages.role_user') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->isBanned())
                                    <span class="px-2.5 py-1 rounded-full text-xs bg-[#f04141]/15 text-[#ff8a8a]">{{ __('messages.admin_banned') }}</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs bg-[#8ee30f]/15 text-[#8ee30f]">{{ __('messages.admin_active') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-300">{{ $user->created_at->format('d.m.Y') }}</td>
                            <td class="px-6 py-4">
                                @if($user->isBanned())
                                    <form action="{{ route('admin.users.unban', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-[#8ee30f]/15 text-[#8ee30f] rounded-full hover:bg-[#8ee30f]/25 transition text-xs">{{ __('messages.admin_unban') }}</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.users.ban', $user) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('messages.admin_ban_confirm') }}');">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-[#f04141]/15 text-[#ff8a8a] rounded-full hover:bg-[#f04141]/25 transition text-xs">{{ __('messages.admin_ban') }}</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-[#7e8488]">{{ __('messages.admin_no_users') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($users->hasPages())
        <div class="mt-6">{{ $users->links() }}</div>
    @endif
</div>
@endsection
