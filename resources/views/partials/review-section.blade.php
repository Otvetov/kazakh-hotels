<div class="bg-white rounded-2xl shadow-sm p-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-gray-900 text-xl font-bold">Отзывы гостей</h2>
        @auth
            <button
                onclick="openReviewModal()"
                class="px-4 py-2 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8c00] transition-colors"
            >
                Оставить отзыв
            </button>
        @endauth
    </div>

    @if($hotel->reviews->count() === 0)
        <div class="text-center py-8 text-gray-500">
            Отзывов пока нет. Будьте первым!
        </div>
    @else
        <div class="space-y-6">
            @foreach($hotel->reviews as $review)
                <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <div class="text-gray-900 mb-1 font-semibold">
                                {{ $review->user->name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $review->created_at->format('d F Y') }}
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5 {{ $i < $review->rating ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300' }}" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-1.07 3.927a1 1 0 01-.39 1.18l-3.462 2.462c-.969.69-2.157-.38-1.902-1.81l1.07-3.292a1 1 0 00-.95-.69H5.577c-.969 0-1.371-1.24-.588-1.81l3.462-2.462a1 1 0 01.39-1.18z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-gray-700">{{ $review->comment }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>

@auth
<!-- Review Modal -->
<div id="reviewModal" class="modal hidden" style="display: none;">
    <div class="modal-box" style="max-width: 28rem;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-gray-900 text-xl font-bold">Оставить отзыв</h3>
            <button
                onclick="closeReviewModal()"
                class="p-2 hover:bg-gray-100 rounded-lg transition-colors"
            >
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('reviews.store') }}" method="POST" id="reviewForm">
            @csrf
            <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-700 mb-2">
                        Оценка <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-2" id="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <button
                                type="button"
                                onclick="setRating({{ $i }})"
                                onmouseenter="hoverRating({{ $i }})"
                                onmouseleave="resetRating()"
                                class="transition-transform hover:scale-110"
                            >
                                <svg class="w-8 h-8 rating-star" data-rating="{{ $i }}" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-1.07 3.927a1 1 0 01-.39 1.18l-3.462 2.462c-.969.69-2.157-.38-1.902-1.81l1.07-3.292a1 1 0 00-.95-.69H5.577c-.969 0-1.371-1.24-.588-1.81l3.462-2.462a1 1 0 01.39-1.18z"/>
                                </svg>
                            </button>
                        @endfor
                        <span id="rating-text" class="ml-2 text-sm text-gray-600"></span>
                    </div>
                    <input type="hidden" name="rating" id="rating-input" required>
                </div>

                <div>
                    <label class="block text-sm text-gray-700 mb-2">
                        Комментарий <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        name="comment"
                        required
                        rows="5"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#38b000] focus:border-transparent resize-none bg-white text-gray-900"
                        placeholder="Расскажите о вашем опыте пребывания в отеле..."
                    ></textarea>
                </div>

                <div class="flex gap-3">
                    <button
                        type="button"
                        onclick="closeReviewModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Отмена
                    </button>
                    <button
                        type="submit"
                        id="submit-review-btn"
                        disabled
                        class="flex-1 px-4 py-2 bg-[#38b000] text-white rounded-lg hover:bg-[#2d8c00] transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed"
                    >
                        Отправить
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let selectedRating = 0;
let hoveredRating = 0;

function openReviewModal() {
    document.getElementById('reviewModal').classList.remove('hidden');
    document.getElementById('reviewModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeReviewModal() {
    document.getElementById('reviewModal').classList.add('hidden');
    document.getElementById('reviewModal').style.display = 'none';
    document.body.style.overflow = '';
    selectedRating = 0;
    hoveredRating = 0;
    updateStars();
    document.getElementById('reviewForm').reset();
}

function setRating(rating) {
    selectedRating = rating;
    document.getElementById('rating-input').value = rating;
    document.getElementById('submit-review-btn').disabled = false;
    updateStars();
}

function hoverRating(rating) {
    hoveredRating = rating;
    updateStars();
}

function resetRating() {
    hoveredRating = 0;
    updateStars();
}

function updateStars() {
    const stars = document.querySelectorAll('.rating-star');
    const rating = hoveredRating || selectedRating;
    const ratingText = document.getElementById('rating-text');
    
    stars.forEach((star, index) => {
        const starRating = parseInt(star.getAttribute('data-rating'));
        if (starRating <= rating) {
            star.classList.add('fill-yellow-400', 'text-yellow-400');
            star.classList.remove('text-gray-300');
        } else {
            star.classList.remove('fill-yellow-400', 'text-yellow-400');
            star.classList.add('text-gray-300');
        }
    });
    
    if (selectedRating > 0) {
        ratingText.textContent = selectedRating + ' из 5';
    } else {
        ratingText.textContent = '';
    }
}

// Close on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeReviewModal();
    }
});

// Close on outside click
document.getElementById('reviewModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeReviewModal();
    }
});
</script>
@endauth


