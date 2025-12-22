<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Get hotel reviews
     */
    public function index(Hotel $hotel): JsonResponse
    {
        $reviews = $hotel->reviews()->with('user')->latest()->get();

        return response()->json([
            'data' => $reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'user_name' => $review->user->name,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at->format('Y-m-d H:i:s'),
                ];
            }),
        ]);
    }

    /**
     * Store review
     */
    public function store(StoreReviewRequest $request, Hotel $hotel): JsonResponse
    {
        $review = \App\Models\Review::create([
            'user_id' => Auth::id(),
            'hotel_id' => $hotel->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Review submitted. It will be visible after admin approval.',
            'data' => [
                'id' => $review->id,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'status' => $review->status,
            ],
        ], 201);
    }
}

