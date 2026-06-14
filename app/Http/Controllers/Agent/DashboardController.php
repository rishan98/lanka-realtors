<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:agent']);
    }

    public function index()
    {
        $user = auth()->user();
        $myListings = $user->listings()->latest()->take(8)->get();

        $stats = [
            'total' => $user->listings()->count(),
            'published' => $user->listings()->where('status', 'published')->count(),
            'draft' => $user->listings()->where('status', 'draft')->count(),
            'phone_leads' => $user->contactLeads()->where('type', 'phone')->count(),
            'email_leads' => $user->contactLeads()->where('type', 'email')->count(),
            'pending_reviews' => $user->pendingReviewCount(),
        ];

        return view('agent.dashboard', compact('myListings', 'stats'));
    }
}
