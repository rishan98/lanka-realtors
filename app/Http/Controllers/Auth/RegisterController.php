<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(Request $request)
    {
        $role = $request->query('role', User::ROLE_AGENT);
        if (! in_array($role, [User::ROLE_AGENT, User::ROLE_OWNER], true)) {
            $role = User::ROLE_AGENT;
        }

        return view('auth.register', ['defaultRole' => $role]);
    }

    protected function validator(array $data)
    {
        $role = $data['role'] ?? User::ROLE_AGENT;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:32'],
            'role' => ['required', 'string', Rule::in([User::ROLE_AGENT, User::ROLE_OWNER])],
        ];

        if ($role === User::ROLE_AGENT) {
            $rules['agency_name'] = ['nullable', 'string', 'max:255'];
            $rules['avatar'] = ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'];
        }

        return Validator::make($data, $rules);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->createUser($request);

        event(new Registered($user));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return redirect($this->redirectPath());
    }

    protected function createUser(Request $request): User
    {
        $role = $request->input('role', User::ROLE_AGENT);
        $avatarPath = null;

        if ($role === User::ROLE_AGENT && $request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('agents/avatars', 'public');
        }

        if ($role === User::ROLE_AGENT && ! $avatarPath) {
            throw ValidationException::withMessages([
                'avatar' => 'Please upload a profile photo to register as an agent.',
            ]);
        }

        return User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $role,
            'approval_status' => User::APPROVAL_PENDING,
            'phone' => $request->input('phone'),
            'agency_name' => $role === User::ROLE_AGENT
                ? $request->input('agency_name')
                : null,
            'avatar_path' => $avatarPath,
        ]);
    }

    protected function registered(Request $request, $user)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('register.pending')
            ->with('status', 'Your account has been submitted. An administrator will review your registration before you can log in.');
    }
}
