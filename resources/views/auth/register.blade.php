@extends('layouts.portal')

@section('title', 'Register — '.config('app.name'))

@section('content')
@php
    $roles = config('users.roles', []);
    $selectedRole = old('role', $defaultRole ?? request('role', \App\Models\User::ROLE_AGENT));
    if (! array_key_exists($selectedRole, $roles)) {
        $selectedRole = \App\Models\User::ROLE_AGENT;
    }
@endphp
<div class="auth-wrap">
    <div class="glass auth-card">
        <h1>Create account</h1>
        <p class="muted">Register as an agent or property owner to post listings on Lanka Realtors. Your account must be approved by an administrator before you can log in.</p>

        <form method="POST" action="{{ route('register') }}" class="mt-3" id="register-form">
            @csrf

            <div class="field">
                <span class="field-label">I am registering as</span>
                <div class="register-role-options mt-2">
                    @foreach($roles as $value => $label)
                        @if($value !== 'admin')
                            <label class="register-role-option">
                                <input type="radio" name="role" value="{{ $value }}" {{ $selectedRole === $value ? 'checked' : '' }} required>
                                <span>{{ $label }}</span>
                            </label>
                        @endif
                    @endforeach
                </div>
                @error('role')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="field mt-2">
                <label for="name">Full name</label>
                <input id="name" type="text" class="input @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="field mt-2" id="agency-field" data-show-for="agent">
                <label for="agency_name">Agency (optional)</label>
                <input id="agency_name" type="text" class="input @error('agency_name') is-invalid @enderror" name="agency_name" value="{{ old('agency_name') }}" autocomplete="organization">
                @error('agency_name')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="field mt-2">
                <label for="phone">Phone (optional)</label>
                <input id="phone" type="text" class="input @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" autocomplete="tel">
                @error('phone')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="field mt-2">
                <label for="email">Email</label>
                <input id="email" type="email" class="input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                @error('email')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="field mt-2">
                <label for="password">Password</label>
                <input id="password" type="password" class="input @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                @error('password')<div class="error-text">{{ $message }}</div>@enderror
            </div>

            <div class="field mt-2">
                <label for="password-confirm">Confirm password</label>
                <input id="password-confirm" type="password" class="input" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="mt-3">
                <button type="submit" class="btn-gold">Create account</button>
            </div>
        </form>

        <p class="muted mt-3 mb-0">Already registered? <a href="{{ route('login') }}">Login</a></p>
    </div>
</div>

<style>
.register-role-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.register-role-option {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 14px;
    border: 1px solid rgba(11, 27, 51, 0.12);
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    color: var(--navy);
    background: #fff;
}
.register-role-option:has(input:checked) {
    border-color: rgba(201, 162, 39, 0.6);
    background: rgba(201, 162, 39, 0.08);
}
.register-role-option input {
    margin: 0;
}
</style>

<script>
(function () {
    var form = document.getElementById('register-form');
    var agencyField = document.getElementById('agency-field');

    function updateRoleFields() {
        var role = form.querySelector('input[name="role"]:checked');
        var isAgent = role && role.value === 'agent';
        agencyField.style.display = isAgent ? '' : 'none';
    }

    form.querySelectorAll('input[name="role"]').forEach(function (input) {
        input.addEventListener('change', updateRoleFields);
    });

    updateRoleFields();
})();
</script>
@endsection
