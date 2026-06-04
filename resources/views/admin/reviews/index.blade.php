@extends('layouts.app')

@section('title', 'Модерация отзывов - Админ')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-extrabold text-white mb-6">Модерация отзывов</h1>

    <form method="GET" class="flex gap-3 mb-5">
        <select name="status" class="field-input max-w-xs" style="color-scheme: dark;">
            <option value="">Все статусы</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Ожидает</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Одобрено</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Отклонено</option>
        </select>
        <button type="submit" class="btn-dark px-6">Фильтр</button>
    </form>

    <div class="space-y-4">
        @forelse($reviews as $review)
            <div class="otl-surface p-6">
                <div class="flex justify-between items-start mb-4 gap-4">
                    <div>
                        <p class="font-semibold text-white">{{ $review->user->name }}</p>
                        <p class="text-sm text-[#7e8488]">{{ $review->hotel->name }}</p>
                        <div class="flex items-center mt-2">
                            @for($i = 0; $i < 5; $i++)
                                <span class="text-yellow-400 {{ $i < $review->rating ? '' : 'opacity-30' }}">★</span>
                            @endfor
                            <span class="ml-2 text-gray-300 text-sm">{{ $review->rating }}/5</span>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs flex-shrink-0
                        {{ $review->status === 'approved' ? 'bg-[#8ee30f]/15 text-[#8ee30f]' :
                           ($review->status === 'rejected' ? 'bg-[#f04141]/15 text-[#ff8a8a]' :
                            'bg-yellow-400/15 text-yellow-300') }}">
                        @if($review->status === 'approved') Одобрено
                        @elseif($review->status === 'rejected') Отклонено
                        @else Ожидает @endif
                    </span>
                </div>
                <p class="text-gray-300 mb-4">{{ $review->comment }}</p>
                <div class="flex gap-3">
                    @if($review->status !== 'approved')
                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-5 py-2 bg-[#8ee30f]/15 text-[#8ee30f] rounded-full hover:bg-[#8ee30f]/25 transition text-sm font-medium">Одобрить</button>
                        </form>
                    @endif
                    @if($review->status !== 'rejected')
                        <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-5 py-2 bg-[#f04141]/15 text-[#ff8a8a] rounded-full hover:bg-[#f04141]/25 transition text-sm font-medium">Отклонить</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="otl-surface p-12 text-center">
                <p class="text-[#7e8488]">Отзывы не найдены</p>
            </div>
        @endforelse
    </div>

    @if($reviews->hasPages())
        <div class="mt-6">{{ $reviews->links() }}</div>
    @endif
</div>
@endsection
