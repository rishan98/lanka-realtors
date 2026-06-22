@extends('layouts.admin')

@section('title', 'Agent reviews — '.config('app.name'))

@section('admin_main')
<header class="agent-page-head">
    <div class="row-flex" style="justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
        <div>
            <h1>Agent reviews</h1>
            <p>Approve or reject reviews before they appear on agent portfolio pages.</p>
        </div>
        <a class="pill" href="{{ route('admin.dashboard') }}">← Dashboard</a>
    </div>
</header>

@if(session('status'))
    <div class="flash agent-flash">{{ session('status') }}</div>
@endif

<div class="admin-stats">
    <article class="admin-stat admin-stat--pending">
        <div class="admin-stat__label">Pending approval</div>
        <div class="admin-stat__value">{{ $pendingCount }}</div>
    </article>
</div>

<section class="admin-panel">
    <div class="agent-panel__head">
        <h2>Pending reviews</h2>
    </div>

    @if($pendingReviews->isEmpty())
        <p class="admin-empty mb-0">No reviews waiting for approval.</p>
    @else
        <div class="agent-review-queue">
            @foreach($pendingReviews as $review)
                <article class="agent-review-card agent-review-card--pending">
                    <header class="agent-review-card__head">
                        <div>
                            <span class="agent-review-card__stars" aria-label="{{ $review->rating }} out of 5 stars">{{ $review->starsLabel() }}</span>
                            <span class="agent-review-card__meta">
                                {{ $review->email }} · {{ $review->agent?->name ?? 'Unknown agent' }} · {{ $review->created_at->format('M j, Y g:i A') }}
                            </span>
                        </div>
                        <span class="agent-review-card__badge agent-review-card__badge--pending">Pending</span>
                    </header>
                    <p class="agent-review-card__message">{{ $review->message }}</p>
                    <div class="agent-review-card__actions">
                        @if($review->agent)
                            <a class="pill" href="{{ route('agents.portfolio', $review->agent) }}" target="_blank" rel="noopener">View portfolio</a>
                        @endif
                        <form method="post" action="{{ route('admin.reviews.approve', $review) }}">
                            @csrf
                            <button type="submit" class="btn-gold">Approve & publish</button>
                        </form>
                        <form method="post" action="{{ route('admin.reviews.reject', $review) }}">
                            @csrf
                            <button type="submit" class="pill">Reject</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>

@if($recentApproved->isNotEmpty())
    <section class="admin-panel">
        <div class="agent-panel__head">
            <h2>Recently approved</h2>
        </div>
        <div class="agent-review-queue">
            @foreach($recentApproved as $review)
                <article class="agent-review-card">
                    <header class="agent-review-card__head">
                        <div>
                            <span class="agent-review-card__stars" aria-label="{{ $review->rating }} out of 5 stars">{{ $review->starsLabel() }}</span>
                            <span class="agent-review-card__meta">
                                {{ $review->maskedEmail() }} · {{ $review->agent?->name ?? 'Unknown agent' }} · {{ $review->created_at->format('M j, Y') }}
                            </span>
                        </div>
                        <span class="agent-review-card__badge agent-review-card__badge--approved">Published</span>
                    </header>
                    <p class="agent-review-card__message">{{ $review->message }}</p>
                </article>
            @endforeach
        </div>
    </section>
@endif

@if($recentRejected->isNotEmpty())
    <section class="admin-panel">
        <div class="agent-panel__head">
            <h2>Recently rejected</h2>
        </div>
        <div class="agent-review-queue">
            @foreach($recentRejected as $review)
                <article class="agent-review-card agent-review-card--muted">
                    <header class="agent-review-card__head">
                        <div>
                            <span class="agent-review-card__stars" aria-label="{{ $review->rating }} out of 5 stars">{{ $review->starsLabel() }}</span>
                            <span class="agent-review-card__meta">
                                {{ $review->maskedEmail() }} · {{ $review->agent?->name ?? 'Unknown agent' }} · {{ $review->created_at->format('M j, Y') }}
                            </span>
                        </div>
                        <span class="agent-review-card__badge agent-review-card__badge--rejected">Rejected</span>
                    </header>
                    <p class="agent-review-card__message">{{ $review->message }}</p>
                </article>
            @endforeach
        </div>
    </section>
@endif
@endsection
