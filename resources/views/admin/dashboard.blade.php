@extends('layouts.admin')

@section('title', 'Admin dashboard — '.config('app.name'))

@section('admin_main')
@php($user = auth()->user())

<div class="admin-hero">
    <h1>Admin dashboard</h1>
    <p>Review registrations, monitor users, and keep Lanka Realtors running smoothly.</p>
    <div class="admin-hero__meta">Signed in as {{ $user->email }}</div>
</div>

@if($counts['pending'] > 0)
    <div class="admin-alert" role="status">
        <p><strong>{{ $counts['pending'] }}</strong> registration{{ $counts['pending'] === 1 ? '' : 's' }} awaiting your approval.</p>
        <a class="btn-gold" href="{{ route('admin.users.pending') }}">Review now</a>
    </div>
@endif

@if($pendingReviewCount > 0)
    <div class="admin-alert" role="status">
        <p><strong>{{ $pendingReviewCount }}</strong> agent review{{ $pendingReviewCount === 1 ? '' : 's' }} awaiting your approval.</p>
        <a class="btn-gold" href="{{ route('admin.reviews.index') }}">Moderate reviews</a>
    </div>
@endif

<section aria-label="User statistics">
    <div class="admin-stats">
        <article class="admin-stat admin-stat--admins">
            <div class="admin-stat__label">Admins</div>
            <div class="admin-stat__value">{{ $counts['admins'] }}</div>
        </article>
        <article class="admin-stat admin-stat--agents">
            <div class="admin-stat__label">Agents</div>
            <div class="admin-stat__value">{{ $counts['agents'] }}</div>
            <div class="admin-stat__hint">Approved accounts</div>
        </article>
        <article class="admin-stat admin-stat--owners">
            <div class="admin-stat__label">Owners</div>
            <div class="admin-stat__value">{{ $counts['owners'] }}</div>
        </article>
        <article class="admin-stat admin-stat--pending">
            <div class="admin-stat__label">Pending approval</div>
            <div class="admin-stat__value">{{ $counts['pending'] }}</div>
            @if($counts['pending'] > 0)
                <div class="admin-stat__hint">Action required</div>
            @endif
        </article>
    </div>
</section>

<section aria-label="Listing statistics">
    <div class="admin-stats">
        <article class="admin-stat admin-stat--listings">
            <div class="admin-stat__label">Total listings</div>
            <div class="admin-stat__value">{{ $listingStats['total'] }}</div>
        </article>
        <article class="admin-stat admin-stat--published">
            <div class="admin-stat__label">Published</div>
            <div class="admin-stat__value">{{ $listingStats['published'] }}</div>
        </article>
        <article class="admin-stat admin-stat--drafts">
            <div class="admin-stat__label">Drafts</div>
            <div class="admin-stat__value">{{ $listingStats['draft'] }}</div>
        </article>
        <article class="admin-stat admin-stat--inquiries">
            <div class="admin-stat__label">Contact inquiries</div>
            <div class="admin-stat__value">{{ $inquiryCount }}</div>
        </article>
    </div>
</section>

<div class="admin-actions">
    <a class="btn-gold" href="{{ route('admin.users.pending') }}">Pending registrations</a>
    <a class="pill" href="{{ route('admin.contact-inquiries.index') }}">Contact inquiries</a>
    <a class="pill" href="{{ route('admin.listings.index') }}">Manage property ads</a>
    <a class="pill" href="{{ route('portal.home') }}">Public homepage</a>
</div>

<div class="admin-grid-2">
    <section class="admin-panel">
        <div class="admin-panel__head">
            <h2>Pending registrations</h2>
            @if($counts['pending'] > 0)
                <a class="pill" href="{{ route('admin.users.pending') }}">View all</a>
            @endif
        </div>

        @if($pendingUsers->isEmpty())
            <p class="admin-empty">No accounts are waiting for approval.</p>
        @else
            @foreach($pendingUsers as $pendingUser)
                <div class="admin-pending-row">
                    <div class="admin-pending-row__info">
                        <strong>{{ $pendingUser->name }}</strong>
                        <span>{{ $pendingUser->roleLabel() }} · {{ $pendingUser->email }} · {{ $pendingUser->created_at->format('M j, Y') }}</span>
                    </div>
                    <div class="admin-pending-row__actions">
                        <form method="POST" action="{{ route('admin.users.approve', $pendingUser) }}">
                            @csrf
                            <button type="submit" class="btn-gold">Approve</button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.reject', $pendingUser) }}" onsubmit="return confirm('Reject this registration?');">
                            @csrf
                            <button type="submit" class="btn-reject">Reject</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </section>

    <section class="admin-panel">
        <div class="admin-panel__head">
            <h2>Recent contact inquiries</h2>
            @if($inquiryCount > 0)
                <a class="pill" href="{{ route('admin.contact-inquiries.index') }}">View all</a>
            @endif
        </div>

        @if($recentInquiries->isEmpty())
            <p class="admin-empty">No contact messages yet.</p>
        @else
            @foreach($recentInquiries as $inquiry)
                <div class="admin-pending-row">
                    <div class="admin-pending-row__info">
                        <strong>{{ $inquiry->name }}</strong>
                        <span>{{ $inquiry->email }} · {{ $inquiry->created_at->format('M j, Y') }}</span>
                        <span class="muted" style="display:block;margin-top:4px;font-size:0.88rem">{{ Str::limit($inquiry->message, 100) }}</span>
                    </div>
                    <div class="admin-pending-row__actions">
                        <a class="pill" href="{{ route('admin.contact-inquiries.show', $inquiry) }}">View</a>
                    </div>
                </div>
            @endforeach
        @endif
    </section>
</div>
@endsection
