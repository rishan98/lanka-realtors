@extends('layouts.portal')

@section('title', 'Verify email — '.config('app.name'))

@section('content')
<div class="auth-wrap">
    <div class="glass auth-card">
        <h1>Verify your email</h1>
        @if (session('resent'))
            <div class="flash">A fresh verification link has been sent to your email address.</div>
        @endif
        <p class="muted">Before continuing, please check your email for a verification link.</p>
        <form class="mt-3" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn-gold">Resend verification email</button>
        </form>
    </div>
</div>
@endsection
