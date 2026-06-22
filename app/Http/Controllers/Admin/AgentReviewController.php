<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentReview;
use Illuminate\Http\RedirectResponse;

class AgentReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $pendingReviews = AgentReview::query()
            ->pending()
            ->with('agent')
            ->latest()
            ->get();

        $recentApproved = AgentReview::query()
            ->approved()
            ->with('agent')
            ->latest('moderated_at')
            ->take(20)
            ->get();

        $recentRejected = AgentReview::query()
            ->where('status', AgentReview::STATUS_REJECTED)
            ->with('agent')
            ->latest('moderated_at')
            ->take(10)
            ->get();

        return view('admin.reviews.index', [
            'pendingReviews' => $pendingReviews,
            'recentApproved' => $recentApproved,
            'recentRejected' => $recentRejected,
            'pendingCount' => $pendingReviews->count(),
        ]);
    }

    public function approve(AgentReview $review): RedirectResponse
    {
        if (! $review->isPending()) {
            return back()->with('status', 'That review has already been moderated.');
        }

        $review->update([
            'status' => AgentReview::STATUS_APPROVED,
            'moderated_at' => now(),
        ]);

        return back()->with('status', 'Review approved and published on the agent portfolio.');
    }

    public function reject(AgentReview $review): RedirectResponse
    {
        if (! $review->isPending()) {
            return back()->with('status', 'That review has already been moderated.');
        }

        $review->update([
            'status' => AgentReview::STATUS_REJECTED,
            'moderated_at' => now(),
        ]);

        return back()->with('status', 'Review rejected and hidden from the agent portfolio.');
    }
}
