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

        <form method="POST" action="{{ route('register') }}" class="mt-3" id="register-form" enctype="multipart/form-data">
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

            <div class="field mt-2" id="avatar-field" data-show-for="agent">
                <label for="avatar">Profile photo</label>
                <div class="register-avatar-picker">
                    <img id="avatar-preview" class="register-avatar-picker__preview" src="" alt="" hidden>
                    <span class="register-avatar-picker__placeholder" id="avatar-placeholder">Choose a clear headshot</span>
                    <input id="avatar" type="file" class="register-avatar-picker__input @error('avatar') is-invalid @enderror" name="avatar" accept="image/jpeg,image/png,image/webp">
                </div>
                <p class="register-avatar-hint muted">Required for agents. JPG, PNG or WebP, up to 2 MB.</p>
                @error('avatar')<div class="error-text">{{ $message }}</div>@enderror
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
.register-avatar-picker {
    position: relative;
    display: flex;
    align-items: center;
    gap: 12px;
    min-height: 72px;
    padding: 12px 14px;
    border: 1px dashed rgba(11, 27, 51, 0.18);
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.9);
}
.register-avatar-picker__preview {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    object-fit: cover;
    flex-shrink: 0;
    border: 1px solid rgba(11, 27, 51, 0.08);
}
.register-avatar-picker__placeholder {
    flex: 1;
    min-width: 0;
    font-size: 0.92rem;
    font-weight: 600;
    color: var(--navy);
}
.register-avatar-picker__input {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
}
.register-avatar-hint {
    margin: 8px 0 0;
    font-size: 0.82rem;
}
</style>

<script>
(function () {
    var form = document.getElementById('register-form');
    var agencyField = document.getElementById('agency-field');
    var avatarField = document.getElementById('avatar-field');
    var avatarInput = document.getElementById('avatar');
    var avatarPreview = document.getElementById('avatar-preview');
    var avatarPlaceholder = document.getElementById('avatar-placeholder');

    function updateRoleFields() {
        var role = form.querySelector('input[name="role"]:checked');
        var isAgent = role && role.value === 'agent';
        agencyField.style.display = isAgent ? '' : 'none';
        avatarField.style.display = isAgent ? '' : 'none';
        avatarInput.required = isAgent;

        if (isAgent) {
            avatarInput.setAttribute('name', 'avatar');
        } else {
            avatarInput.removeAttribute('name');
            avatarInput.value = '';
            avatarPreview.hidden = true;
            avatarPreview.src = '';
            avatarPlaceholder.hidden = false;
        }
    }

    avatarInput.addEventListener('change', function () {
        var file = avatarInput.files && avatarInput.files[0];
        if (!file) {
            avatarPreview.hidden = true;
            avatarPreview.src = '';
            avatarPlaceholder.hidden = false;
            return;
        }

        avatarPreview.src = URL.createObjectURL(file);
        avatarPreview.hidden = false;
        avatarPlaceholder.hidden = true;
    });

    form.querySelectorAll('input[name="role"]').forEach(function (input) {
        input.addEventListener('change', updateRoleFields);
    });

    updateRoleFields();
})();
</script>
@endsection
