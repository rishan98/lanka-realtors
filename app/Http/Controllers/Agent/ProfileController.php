<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function portalPrefix(): string
    {
        return request()->routeIs('owner.*') ? 'owner' : 'agent';
    }

    public function edit()
    {
        $user = auth()->user();

        return view('agent.profile.edit', [
            'user' => $user,
            'portalPrefix' => $this->portalPrefix(),
            'isOwner' => $user->isOwner(),
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $isOwner = $user->isOwner();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:32',
            'bio' => 'nullable|string|max:2000',
            'avatar' => 'nullable|image|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ];

        if (! $isOwner) {
            $rules['agency_name'] = 'nullable|string|max:255';
            $rules['operating_since_year'] = 'nullable|integer|min:1950|max:'.(date('Y') + 1);
            $rules['buyers_served_estimate'] = 'nullable|integer|min:0|max:10000000';
            $rules['company_logo'] = 'nullable|image|max:2048';
            $rules['cover'] = 'nullable|image|max:4096';
        }

        $data = $request->validate($rules);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;
        $user->bio = $data['bio'] ?? null;

        if (! $isOwner) {
            $user->agency_name = $data['agency_name'] ?? null;
            $user->operating_since_year = $data['operating_since_year'] ?? null;
            $user->buyers_served_estimate = $data['buyers_served_estimate'] ?? null;
        }

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $folder = $isOwner ? 'owners/avatars' : 'agents/avatars';
            $user->avatar_path = $request->file('avatar')->store($folder, 'public');
        }

        if (! $isOwner && $request->hasFile('cover')) {
            if ($user->cover_path) {
                Storage::disk('public')->delete($user->cover_path);
            }
            $user->cover_path = $request->file('cover')->store('agents/covers', 'public');
        }

        if (! $isOwner && $request->hasFile('company_logo')) {
            if ($user->company_logo_path) {
                Storage::disk('public')->delete($user->company_logo_path);
            }
            $user->company_logo_path = $request->file('company_logo')->store('agents/logos', 'public');
        }

        $user->save();

        $prefix = $this->portalPrefix();

        return redirect()->route($prefix.'.profile.edit')->with('status', 'Profile updated successfully.');
    }
}
