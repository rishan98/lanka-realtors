<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        ];

        return view('admin.dashboard', compact('counts'));
    }
}
