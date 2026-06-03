@extends('layouts.portal')

@section('title', 'Reset password — '.config('app.name'))

@section('content')
<div class="auth-wrap">
    <div class="glass auth-card">
        <h1>Reset password</h1>
        @if (session('status'))
            <div class="flash">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('password.email') }}" class="mt-3">
            @csrf
            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" class="input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="mt-3">
                <button type="submit" class="btn-gold">Send reset link</button>
            </div>
        </form>
    </div>
</div>
@endsection
