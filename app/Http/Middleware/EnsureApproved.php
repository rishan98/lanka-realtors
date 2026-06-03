<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureApproved
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user || $user->isAdmin() || ! $user->requiresApproval() || $user->isApproved()) {
            return $next($request);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withErrors(['email' => $user->loginBlockedMessage()]);
    }
}
