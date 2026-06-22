<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\AgentReview;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:agent', 'approved']);
    }

    public function index()
    {
        $user = auth()->user();

        $pendingReviews = $user->reviews()->pending()->latest()->get();
        $approvedReviews = $user->reviews()->approved()->latest()->take(30)->get();
        $rejectedReviews = $user->reviews()
            ->where('status', AgentReview::STATUS_REJECTED)
            ->latest()
            ->take(10)
            ->get();

        return view('agent.reviews.index', [
            'pendingReviews' => $pendingReviews,
            'approvedReviews' => $approvedReviews,
            'rejectedReviews' => $rejectedReviews,
            'approvedCount' => $user->approvedReviewCount(),
            'pendingCount' => $user->pendingReviewCount(),
        ]);
    }
}
