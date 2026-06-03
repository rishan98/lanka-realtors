@extends('layouts.portal')

@section('title', 'Admin dashboard — '.config('app.name'))

@section('content')
<div class="auth-wrap" style="align-items:flex-start;padding-top:48px">
    <div class="glass auth-card" style="max-width:720px">
        <h1>Admin dashboard</h1>
        <p class="muted">Manage users and platform activity.</p>

        <div class="agent-stats mt-3">
            <article class="agent-stat">
                <div class="agent-stat__label">Admins</div>
                <div class="agent-stat__value">{{ $counts['admins'] }}</div>
            </article>
            <article class="agent-stat">
                <div class="agent-stat__label">Agents</div>
                <div class="agent-stat__value">{{ $counts['agents'] }}</div>
            </article>
            <article class="agent-stat">
                <div class="agent-stat__label">Owners</div>
                <div class="agent-stat__value">{{ $counts['owners'] }}</div>
            </article>
        </div>
    </div>
</div>
@endsection
