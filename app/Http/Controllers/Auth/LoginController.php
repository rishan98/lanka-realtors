<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function attemptLogin(Request $request)
    {
        if (! $this->guard()->attempt($this->credentials($request), $request->boolean('remember'))) {
            return false;
        }

        $user = $this->guard()->user();

        if ($user->requiresApproval() && ! $user->isApproved()) {
            $this->guard()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                $this->username() => [$user->loginBlockedMessage()],
            ]);
        }

        return true;
    }

    protected function authenticated(Request $request, $user)
    {
        return redirect()->intended(RouteServiceProvider::homeFor($user));
    }
}
