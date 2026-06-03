<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        }

        return Validator::make($data, $rules);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'phone' => $data['phone'] ?? null,
            'agency_name' => ($data['role'] ?? User::ROLE_AGENT) === User::ROLE_AGENT
                ? ($data['agency_name'] ?? null)
                : null,
        ]);
    }

    protected function registered(Request $request, $user)
    {
        return redirect(RouteServiceProvider::homeFor($user));
    }
}
