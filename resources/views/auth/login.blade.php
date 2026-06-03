@extends('layouts.portal')

@section('title', 'Login — '.config('app.name'))

@section('content')
<div class="auth-wrap">
    <div class="glass auth-card">
        <h1>Agent login</h1>
        <p class="muted">Access your dashboard to post and manage listings.</p>

        <form method="POST" action="{{ route('login') }}" class="mt-3">
            @csrf
            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" class="input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
                @error('email')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="field mt-2">
                <label for="password">Password</label>
                <input id="password" type="password" class="input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="row-flex mt-2">
                <label class="muted" style="font-size:0.92rem;display:flex;gap:8px;align-items:center">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>
            </div>
            <div class="mt-3 row-flex" style="gap:12px">
                <button type="submit" class="btn-gold">Login</button>
                @if (Route::has('password.request'))
                    <a class="pill" href="{{ route('password.request') }}">Forgot password</a>
                @endif
            </div>
        </form>
        <p class="muted mt-3 mb-0">New here? <a href="{{ route('register') }}">Create an account</a></p>
    </div>
</div>
@endsection
