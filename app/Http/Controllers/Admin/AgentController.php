<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(): View
    {
        $agents = User::query()
            ->agents()
            ->approved()
            ->withPublishedListingCounts()
            ->orderByRaw('CASE WHEN rating IS NULL OR rating <= 0 THEN 1 ELSE 0 END')
            ->orderByDesc('rating')
            ->orderBy('name')
            ->get();

        $heroAgentIds = User::query()
            ->agents()
            ->approved()
            ->orderedByRating()
            ->take(3)
            ->pluck('id');

        return view('admin.agents.index', compact('agents', 'heroAgentIds'));
    }

    public function updateRating(Request $request, User $user): RedirectResponse
    {
        if (! $user->isAgent() || ! $user->isApproved()) {
            return back()->with('status', 'Only approved agents can be rated.');
        }

        $data = $request->validate([
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
        ]);

        $rating = $data['rating'];
        $user->rating = ($rating === null || $rating === '') ? null : round((float) $rating, 1);
        $user->save();

        return back()->with('status', $user->name.'\'s rating has been updated.');
    }

    public function updateAvatar(Request $request, User $user): RedirectResponse
    {
        if (! $user->isAgent()) {
            return back()->with('status', 'Only agent profile photos can be updated here.');
        }

        $data = $request->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ]);

        $user->avatar_path = $data['avatar']->store('agents/avatars', 'public');
        $user->save();

        return back()->with('status', $user->name.'\'s profile image has been updated.');
    }
}
