<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store review
     */
    public function store(StoreReviewRequest $request)
    {
        Review::create([
            'user_id' => Auth::id(),
            'hotel_id' => $request->hotel_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Review submitted. It will be visible after admin approval.');
    }
}

