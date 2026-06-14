@extends('layouts.owner')

@section('title', 'Owner dashboard — '.config('app.name'))

@section('agent_main')
@php($user = auth()->user())

<header class="agent-page-head">
    <h1>Welcome, {{ $user->name }}</h1>
    <p>Post and manage your property listings on Lanka Realtors.</p>
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
</div>

<div class="agent-actions">
    <a class="btn-gold" href="{{ route('owner.listings.create') }}">Post Your Ad</a>
    <a class="pill" href="{{ route('owner.listings.index') }}">All listings</a>
    <a class="pill" href="{{ route('owner.profile.edit') }}">Edit profile</a>
</div>

<section class="agent-panel">
    <div class="agent-panel__head">
        <h2>Your profile</h2>
        <a class="pill" href="{{ route('owner.profile.edit') }}">Update</a>
    </div>
    <div class="agent-profile-preview">
        <div class="agent-profile-preview__media">
            <div class="agent-profile-preview__img">
                <img src="{{ $user->avatarUrl() }}" alt="Avatar">
                <span>Photo</span>
            </div>
        </div>
        <div class="agent-profile-preview__body">
            <dl>
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
        <a class="pill" href="{{ route('owner.listings.index') }}">View all</a>
    </div>

    @if($myListings->isEmpty())
        <p class="mb-0" style="color:#5a6578">You have not created a listing yet. <a href="{{ route('owner.listings.create') }}">Post your first property</a>.</p>
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
                            <a href="{{ route('owner.listings.edit', $listing) }}">{{ $listing->title }}</a>
                        </h3>
                        <a class="pill" href="{{ route('owner.listings.edit', $listing) }}">Edit</a>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>
@endsection
