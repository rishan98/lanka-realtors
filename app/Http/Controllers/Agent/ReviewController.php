<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\AgentReview;
use Illuminate\Http\RedirectResponse;

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

    public function approve(AgentReview $review): RedirectResponse
    {
        $this->authorizeReview($review);

        if (! $review->isPending()) {
            return back()->with('status', 'That review has already been moderated.');
        }

        $review->update([
            'status' => AgentReview::STATUS_APPROVED,
            'moderated_at' => now(),
        ]);

        return back()->with('status', 'Review approved and published on your portfolio.');
    }

    public function reject(AgentReview $review): RedirectResponse
    {
        $this->authorizeReview($review);

        if (! $review->isPending()) {
            return back()->with('status', 'That review has already been moderated.');
        }

        $review->update([
            'status' => AgentReview::STATUS_REJECTED,
            'moderated_at' => now(),
        ]);

        return back()->with('status', 'Review rejected and hidden from your portfolio.');
    }

    protected function authorizeReview(AgentReview $review): void
    {
        if ($review->agent_id !== auth()->id()) {
            abort(403);
        }
    }
}
