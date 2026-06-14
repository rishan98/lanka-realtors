<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;

class RedirectToUserPortal
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user || $user->isAdmin()) {
            return $next($request);
        }

        if ($user->isOwner() && $request->is('agent', 'agent/*')) {
            return redirect(RouteServiceProvider::portalPathFor($user, $request->path(), $request->getQueryString()));
        }

        if ($user->isAgent() && $request->is('owner', 'owner/*')) {
            return redirect(RouteServiceProvider::portalPathFor($user, $request->path(), $request->getQueryString()));
        }

        return $next($request);
    }
}
