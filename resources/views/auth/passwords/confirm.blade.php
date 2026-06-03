@extends('layouts.portal')

@section('title', 'Confirm password — '.config('app.name'))

@section('content')
<div class="auth-wrap">
    <div class="glass auth-card">
        <h1>Confirm password</h1>
        <p class="muted">Please confirm your password before continuing.</p>
        <form method="POST" action="{{ route('password.confirm') }}" class="mt-3">
            @csrf
            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" class="input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="mt-3 row-flex" style="gap:12px">
                <button type="submit" class="btn-gold">Confirm</button>
                @if (Route::has('password.request'))
                    <a class="pill" href="{{ route('password.request') }}">Forgot password</a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
