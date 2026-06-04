@extends('layouts.app')

@section('title', 'Модерация отзывов - Админ')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Модерация отзывов</h1>

    <div class="mb-4">
        <form method="GET" class="flex gap-4">
            <select name="status" class="px-4 py-2 border border-gray-300border-gray-600 rounded-lg bg-whitebg-gray-700">
                <option value="">Все статусы</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Ожидает</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Одобрено</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Отклонено</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-[#8ee30f] text-white rounded-lg hover:bg-[#2d8a00] transition">Фильтр</button>
        </form>
    </div>

    <div class="space-y-4">
        @forelse($reviews as $review)
            <div class="bg-whitebg-gray-800 rounded-lg shadow-md p-6 border border-gray-200border-gray-700">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="font-semibold">{{ $review->user->name }}</p>
                        <p class="text-sm text-gray-600text-gray-400">{{ $review->hotel->name }}</p>
                        <div class="flex items-center mt-2">
                            @for($i = 0; $i < 5; $i++)
                                <span class="text-yellow-400 {{ $i < $review->rating ? '' : 'opacity-30' }}">★</span>
                            @endfor
                            <span class="ml-2">{{ $review->rating }}/5</span>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded text-sm
                        {{ $review->status === 'approved' ? 'bg-green-100bg-green-900 text-green-800text-green-200' : 
                           ($review->status === 'rejected' ? 'bg-red-100bg-red-900 text-red-800text-red-200' : 
                            'bg-yellow-100bg-yellow-900 text-yellow-800text-yellow-200') }}">
                        @if($review->status === 'approved')
                            Одобрено
                        @elseif($review->status === 'rejected')
                            Отклонено
                        @else
                            Ожидает
                        @endif
                    </span>
                </div>
                <p class="text-gray-700text-gray-300 mb-4">{{ $review->comment }}</p>
                <div class="flex gap-2">
                    @if($review->status !== 'approved')
                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">Одобрить</button>
                        </form>
                    @endif
                    @if($review->status !== 'rejected')
                        <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">Отклонить</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-whitebg-gray-800 rounded-lg shadow-md p-12 text-center border border-gray-200border-gray-700">
                <p class="text-gray-500text-gray-400">Отзывы не найдены</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $reviews->links() }}
    </div>
</div>
@endsection


