@extends('layouts.portal')

@section('title', 'Registration submitted — '.config('app.name'))

@section('content')
<div class="auth-wrap">
    <div class="glass auth-card">
        <h1>Registration submitted</h1>

        @if(session('status'))
            <p class="section-lead mt-2 mb-0">{{ session('status') }}</p>
        @else
            <p class="section-lead mt-2 mb-0">Thank you for registering. An administrator will review your account before you can log in.</p>
        @endif

        <p class="muted mt-3 mb-0">You will receive access once your agent or owner account is approved. If you have questions, contact the site administrator.</p>

        <div class="mt-3">
            <a class="btn-gold" href="{{ route('portal.home') }}">Back to home</a>
            <a class="pill" href="{{ route('login') }}" style="margin-left:10px">Login</a>
        </div>
    </div>
</div>
@endsection
