@extends('layouts.portal')

@section('title', 'Set new password — '.config('app.name'))

@section('content')
<div class="auth-wrap">
    <div class="glass auth-card">
        <h1>Set new password</h1>
        <form method="POST" action="{{ route('password.update') }}" class="mt-3">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" class="input @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autofocus>
                @error('email')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="field mt-2">
                <label for="password">New password</label>
                <input id="password" type="password" class="input @error('password') is-invalid @enderror" name="password" required>
                @error('password')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="field mt-2">
                <label for="password-confirm">Confirm password</label>
                <input id="password-confirm" type="password" class="input" name="password_confirmation" required>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn-gold">Update password</button>
            </div>
        </form>
    </div>
</div>
@endsection
