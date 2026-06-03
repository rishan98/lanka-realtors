<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Models\Listing;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $counts = [
            'admins' => User::where('role', User::ROLE_ADMIN)->count(),
            'agents' => User::where('role', User::ROLE_AGENT)->count(),
            'owners' => User::where('role', User::ROLE_OWNER)->count(),
            'pending' => User::pendingApproval()
                ->whereIn('role', [User::ROLE_AGENT, User::ROLE_OWNER])
                ->count(),
        ];

        $listingStats = [
            'total' => Listing::count(),
            'published' => Listing::where('status', 'published')->count(),
            'draft' => Listing::where('status', 'draft')->count(),
        ];

        $pendingUsers = User::query()
            ->pendingApproval()
            ->whereIn('role', [User::ROLE_AGENT, User::ROLE_OWNER])
            ->orderBy('created_at')
            ->take(5)
            ->get();

        $inquiryCount = ContactInquiry::count();

        $recentInquiries = ContactInquiry::query()
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'counts',
            'listingStats',
            'pendingUsers',
            'inquiryCount',
            'recentInquiries'
        ));
    }
}
