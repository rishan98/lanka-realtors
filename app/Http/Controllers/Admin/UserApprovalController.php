<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $pending = User::query()
            ->agents()
            ->pendingApproval()
            ->orderBy('created_at')
            ->get();

        return view('admin.users.pending', compact('pending'));
    }

    public function approve(User $user): RedirectResponse
    {
        if (! $user->isPendingApproval() || ! $user->requiresApproval()) {
            return back()->with('status', 'This account is not awaiting approval.');
        }

        $user->update(['approval_status' => User::APPROVAL_APPROVED]);

        return back()->with('status', $user->name.' has been approved and can now log in.');
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        if (! $user->isPendingApproval() || ! $user->requiresApproval()) {
            return back()->with('status', 'This account is not awaiting approval.');
        }

        $user->update(['approval_status' => User::APPROVAL_REJECTED]);

        return back()->with('status', $user->name.'\'s registration has been rejected.');
    }
}
