@extends('layouts.app')

@section('title', 'Moderate Reviews - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Moderate Reviews</h1>

    <div class="mb-4">
        <form method="GET" class="flex gap-4">
            <select name="status" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8a00] transition">Filter</button>
        </form>
    </div>

    <div class="space-y-4">
        @forelse($reviews as $review)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="font-semibold">{{ $review->user->name }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $review->hotel->name }}</p>
                        <div class="flex items-center mt-2">
                            @for($i = 0; $i < 5; $i++)
                                <span class="text-yellow-400 {{ $i < $review->rating ? '' : 'opacity-30' }}">★</span>
                            @endfor
                            <span class="ml-2">{{ $review->rating }}/5</span>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded text-sm
                        {{ $review->status === 'approved' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 
                           ($review->status === 'rejected' ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' : 
                            'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200') }}">
                        {{ ucfirst($review->status) }}
                    </span>
                </div>
                <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $review->comment }}</p>
                <div class="flex gap-2">
                    @if($review->status !== 'approved')
                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">Approve</button>
                        </form>
                    @endif
                    @if($review->status !== 'rejected')
                        <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">Reject</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center border border-gray-200 dark:border-gray-700">
                <p class="text-gray-500 dark:text-gray-400">No reviews found</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $reviews->links() }}
    </div>
</div>
@endsection

