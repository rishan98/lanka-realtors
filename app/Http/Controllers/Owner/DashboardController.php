<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:owner']);
    }

    public function index()
    {
        $user = auth()->user();
        $myListings = $user->listings()->latest()->take(8)->get();

        $stats = [
            'total' => $user->listings()->count(),
            'published' => $user->listings()->where('status', 'published')->count(),
            'draft' => $user->listings()->where('status', 'draft')->count(),
        ];

        return view('owner.dashboard', compact('myListings', 'stats'));
    }
}
