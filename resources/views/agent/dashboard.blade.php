@extends('layouts.agent')

@section('title', 'Agent dashboard — '.config('app.name'))

@section('agent_main')
@php($user = auth()->user())

<header class="agent-page-head">
    <h1>Welcome back, {{ $user->name }}</h1>
    <p>Manage your profile, listings, and visibility on Lanka Realtors.</p>
</header>

<div class="agent-stats">
    <article class="agent-stat">
        <div class="agent-stat__label">Total listings</div>
        <div class="agent-stat__value">{{ $stats['total'] }}</div>
    </article>
    <article class="agent-stat">
        <div class="agent-stat__label">Published</div>
        <div class="agent-stat__value">{{ $stats['published'] }}</div>
    </article>
    <article class="agent-stat">
        <div class="agent-stat__label">Drafts</div>
        <div class="agent-stat__value">{{ $stats['draft'] }}</div>
    </article>
    <article class="agent-stat">
        <div class="agent-stat__label">Buyers served</div>
        <div class="agent-stat__value">{{ $user->buyers_served_estimate ? number_format($user->buyers_served_estimate) : '—' }}</div>
        @if($user->operating_since_year)
            <div class="agent-stat__hint">Since {{ $user->operating_since_year }}</div>
        @endif
    </article>
</div>

<div class="agent-actions">
    <a class="btn-gold" href="{{ route('agent.listings.create') }}">New listing</a>
    <a class="pill" href="{{ route('agent.listings.index') }}">All listings</a>
    <a class="pill" href="{{ route('agent.profile.edit') }}">Edit profile</a>
</div>

<section class="agent-panel">
    <div class="agent-panel__head">
        <h2>Your profile</h2>
        <a class="pill" href="{{ route('agent.profile.edit') }}">Update</a>
    </div>
    <div class="agent-profile-preview">
        <div class="agent-profile-preview__media">
            <div class="agent-profile-preview__img">
                <img src="{{ $user->avatarUrl() }}" alt="Avatar">
                <span>Photo</span>
            </div>
            @if($user->companyLogoUrl())
                <div class="agent-profile-preview__img">
                    <img src="{{ $user->companyLogoUrl() }}" alt="Company logo">
                    <span>Agency logo</span>
                </div>
            @endif
        </div>
        <div class="agent-profile-preview__body">
            <dl>
                <dt>Agency</dt>
                <dd>{{ $user->companyDisplayName() }}</dd>
                <dt>Email</dt>
                <dd>{{ $user->email }}</dd>
                <dt>Phone</dt>
                <dd>{{ $user->phone ?: '—' }}</dd>
                @if($user->bio)
                    <dt>Bio</dt>
                    <dd>{{ Str::limit($user->bio, 160) }}</dd>
                @endif
            </dl>
        </div>
    </div>
</section>

<section class="agent-panel">
    <div class="agent-panel__head">
        <h2>Recent listings</h2>
        <a class="pill" href="{{ route('agent.listings.index') }}">View all</a>
    </div>

    @if($myListings->isEmpty())
        <p class="mb-0" style="color:#5a6578">You have not created a listing yet. <a href="{{ route('agent.listings.create') }}">Post your first property</a>.</p>
    @else
        <div class="card-grid">
            @foreach($myListings as $listing)
                <article class="property-card">
                    <a href="{{ route('listings.show', $listing) }}">
                        <img class="property-card__image {{ $listing->cardImageClass() }}" src="{{ $listing->imageUrl() }}" alt="{{ $listing->title }}" loading="lazy" onerror="this.onerror=null;this.src='{{ \App\Models\Listing::defaultImageUrl() }}';this.classList.add('property-card__image--placeholder');">
                    </a>
                    <div class="property-card__body">
                        <div class="property-card__meta">{{ strtoupper($listing->status) }} · {{ $listing->kindLabel() }}</div>
                        <h3 class="property-card__title">
                            <a href="{{ route('agent.listings.edit', $listing) }}">{{ $listing->title }}</a>
                        </h3>
                        <a class="pill" href="{{ route('agent.listings.edit', $listing) }}">Edit</a>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>
@endsection
