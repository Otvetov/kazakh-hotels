<div class="otl-surface p-6 md:p-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-white text-xl font-extrabold">{{ __('messages.guest_reviews') }}</h2>
        @if($canReview)
            <button onclick="openReviewModal()" class="btn-accent px-5 py-2.5">
                {{ __('messages.leave_review') }}
            </button>
        @elseif(auth()->check())
            <span class="text-sm text-[#7e8488] text-right max-w-[16rem]">{{ __('messages.review_requires_stay') }}</span>
        @endif
    </div>

    @if($hotel->reviews->count() === 0)
        <div class="text-center py-8 text-[#7e8488]">
            {{ __('messages.no_reviews_yet') }}
        </div>
    @else
        <div class="space-y-5">
            @foreach($hotel->reviews as $review)
                <div class="bg-[#141516] rounded-2xl p-5">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#8ee30f] text-[#0a0a0a] flex items-center justify-center font-semibold">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-white font-semibold">{{ $review->user->name }}</div>
                                <div class="text-sm text-[#7e8488]">{{ $review->created_at->format('d.m.Y') }}</div>
                            </div>
                        </div>
                        <span class="flex items-center gap-1 bg-[#8ee30f] text-[#0a0a0a] px-2.5 py-1 rounded-lg font-bold text-sm">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.37 4.24a1 1 0 00.95.69h4.46c.97 0 1.37 1.24.59 1.81l-3.61 2.62a1 1 0 00-.36 1.12l1.38 4.24c.3.92-.75 1.69-1.54 1.12l-3.6-2.62a1 1 0 00-1.18 0l-3.6 2.62c-.79.57-1.84-.2-1.54-1.12l1.38-4.24a1 1 0 00-.36-1.12L1.33 9.67c-.78-.57-.38-1.81.59-1.81h4.46a1 1 0 00.95-.69z"/></svg>
                            {{ $review->rating }}
                        </span>
                    </div>
                    <p class="text-gray-300">{{ $review->comment }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>

@if($canReview)
<!-- Review Modal -->
<div id="reviewModal" class="bmodal hidden" style="display: none;">
    <div class="bmodal-box" style="max-width: 28rem;">
        <div class="flex items-center justify-center relative p-6 border-b border-white/10">
            <h3 class="text-white text-lg font-bold">{{ __('messages.leave_review') }}</h3>
            <button onclick="closeReviewModal()" class="absolute right-5 top-1/2 -translate-y-1/2 p-2 bg-white/5 hover:bg-white/10 rounded-full transition-colors">
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('reviews.store') }}" method="POST" id="reviewForm" class="p-6">
            @csrf
            <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">

            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-[#7e8488] mb-2">{{ __('messages.your_rating') }} <span class="text-[#f04141]">*</span></label>
                    <div class="flex items-center gap-3 bg-[#141516] rounded-2xl px-4 py-3">
                        <div class="flex items-center gap-1.5" id="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" onclick="setRating({{ $i }})" onmouseenter="hoverRating({{ $i }})" onmouseleave="resetRating()" class="transition-transform hover:scale-125 p-0.5">
                                    <svg class="rating-star w-8 h-8 text-[#3a3b3c] transition-colors" data-rating="{{ $i }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.37 4.24a1 1 0 00.95.69h4.46c.97 0 1.37 1.24.59 1.81l-3.61 2.62a1 1 0 00-.36 1.12l1.38 4.24c.3.92-.75 1.69-1.54 1.12l-3.6-2.62a1 1 0 00-1.18 0l-3.6 2.62c-.79.57-1.84-.2-1.54-1.12l1.38-4.24a1 1 0 00-.36-1.12L1.33 9.67c-.78-.57-.38-1.81.59-1.81h4.46a1 1 0 00.95-.69z"/>
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        <span id="rating-text" class="text-sm font-semibold text-[#8ee30f]"></span>
                    </div>
                    <input type="hidden" name="rating" id="rating-input" required>
                </div>

                <div>
                    <label class="block text-sm text-[#7e8488] mb-2">{{ __('messages.comment') }} <span class="text-[#f04141]">*</span></label>
                    <textarea name="comment" required minlength="10" rows="5"
                        class="w-full px-4 py-3 bg-[#141516] border border-white/10 rounded-2xl focus:outline-none focus:border-[#8ee30f] resize-none text-white placeholder-[#7e8488]"
                        placeholder="{{ __('messages.review_placeholder') }}"></textarea>
                    <p class="text-xs text-[#7e8488] mt-1.5">{{ __('messages.min_chars') }}</p>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeReviewModal()" class="btn-dark flex-1 py-2.5">{{ __('messages.cancel') }}</button>
                    <button type="submit" id="submit-review-btn" disabled
                        class="btn-accent flex-1 py-2.5 disabled:opacity-40 disabled:cursor-not-allowed">{{ __('messages.submit') }}</button>
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
    document.getElementById('submit-review-btn').disabled = true;
}

function setRating(rating) {
    selectedRating = rating;
    document.getElementById('rating-input').value = rating;
    document.getElementById('submit-review-btn').disabled = false;
    updateStars();
}

function hoverRating(rating) { hoveredRating = rating; updateStars(); }
function resetRating() { hoveredRating = 0; updateStars(); }

const RATING_LABELS = { 1: @json(__('messages.rate_1')), 2: @json(__('messages.rate_2')), 3: @json(__('messages.rate_3')), 4: @json(__('messages.rate_4')), 5: @json(__('messages.rate_5')) };

function updateStars() {
    const stars = document.querySelectorAll('.rating-star');
    const rating = hoveredRating || selectedRating;
    const ratingText = document.getElementById('rating-text');
    stars.forEach((star) => {
        const starRating = parseInt(star.getAttribute('data-rating'));
        const active = starRating <= rating;
        star.classList.toggle('text-[#8ee30f]', active);
        star.classList.toggle('text-[#3a3b3c]', !active);
    });
    ratingText.textContent = rating > 0 ? RATING_LABELS[rating] : '';
}

document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeReviewModal(); });
document.getElementById('reviewModal')?.addEventListener('click', function(e) { if (e.target === this) closeReviewModal(); });

// Отправка отзыва без перезагрузки страницы
const REVIEW_T = { error: @json(__('messages.review_error')) };

document.getElementById('reviewForm')?.addEventListener('submit', async function (e) {
    e.preventDefault();
    const btn = document.getElementById('submit-review-btn');
    btn.disabled = true;

    try {
        const res = await fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: new FormData(this),
        });
        const data = await res.json().catch(() => ({}));

        if (res.ok) {
            closeReviewModal();
            showToast(data.message || '', 'success');
        } else if (res.status === 422 && data.errors) {
            showToast(Object.values(data.errors)[0][0], 'error');
            btn.disabled = false;
        } else {
            showToast(data.message || REVIEW_T.error, 'error');
            btn.disabled = false;
        }
    } catch (err) {
        showToast(REVIEW_T.error, 'error');
        btn.disabled = false;
    }
});
</script>
@endif
