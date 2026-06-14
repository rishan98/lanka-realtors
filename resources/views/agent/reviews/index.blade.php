@extends('layouts.agent')

@section('title', 'Portfolio reviews — '.config('app.name'))

@section('agent_main')
<header class="agent-page-head">
    <div class="row-flex" style="justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
        <div>
            <h1>Portfolio reviews</h1>
            <p>Approve reviews before they appear on your public portfolio page.</p>
        </div>
        <a class="pill" href="{{ route('agents.portfolio', auth()->user()) }}" target="_blank" rel="noopener">View portfolio</a>
    </div>
</header>

@if(session('status'))
    <div class="flash agent-flash">{{ session('status') }}</div>
@endif

<div class="agent-stats">
    <article class="agent-stat">
        <div class="agent-stat__label">Pending approval</div>
        <div class="agent-stat__value">{{ $pendingCount }}</div>
    </article>
    <article class="agent-stat">
        <div class="agent-stat__label">Published reviews</div>
        <div class="agent-stat__value">{{ $approvedCount }}</div>
    </article>
</div>

<section class="agent-panel">
    <div class="agent-panel__head">
        <h2>Pending reviews</h2>
    </div>

    @if($pendingReviews->isEmpty())
        <p class="mb-0" style="color:#5a6578">No reviews waiting for approval.</p>
    @else
        <div class="agent-review-queue">
            @foreach($pendingReviews as $review)
                <article class="agent-review-card agent-review-card--pending">
                    <header class="agent-review-card__head">
                        <div>
                            <span class="agent-review-card__stars" aria-label="{{ $review->rating }} out of 5 stars">{{ $review->starsLabel() }}</span>
                            <span class="agent-review-card__meta">{{ $review->email }} · {{ $review->created_at->format('M j, Y g:i A') }}</span>
                        </div>
                        <span class="agent-review-card__badge agent-review-card__badge--pending">Pending</span>
                    </header>
                    <p class="agent-review-card__message">{{ $review->message }}</p>
                    <div class="agent-review-card__actions">
                        <form method="post" action="{{ route('agent.reviews.approve', $review) }}">
                            @csrf
                            <button type="submit" class="btn-gold">Approve & publish</button>
                        </form>
                        <form method="post" action="{{ route('agent.reviews.reject', $review) }}">
                            @csrf
                            <button type="submit" class="pill">Reject</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>

@if($approvedReviews->isNotEmpty())
    <section class="agent-panel">
        <div class="agent-panel__head">
            <h2>Published on portfolio</h2>
        </div>
        <div class="agent-review-queue">
            @foreach($approvedReviews as $review)
                <article class="agent-review-card">
                    <header class="agent-review-card__head">
                        <div>
                            <span class="agent-review-card__stars" aria-label="{{ $review->rating }} out of 5 stars">{{ $review->starsLabel() }}</span>
                            <span class="agent-review-card__meta">{{ $review->maskedEmail() }} · {{ $review->created_at->format('M j, Y') }}</span>
                        </div>
                        <span class="agent-review-card__badge agent-review-card__badge--approved">Published</span>
                    </header>
                    <p class="agent-review-card__message">{{ $review->message }}</p>
                </article>
            @endforeach
        </div>
    </section>
@endif

@if($rejectedReviews->isNotEmpty())
    <section class="agent-panel">
        <div class="agent-panel__head">
            <h2>Rejected</h2>
        </div>
        <div class="agent-review-queue">
            @foreach($rejectedReviews as $review)
                <article class="agent-review-card agent-review-card--muted">
                    <header class="agent-review-card__head">
                        <div>
                            <span class="agent-review-card__stars" aria-label="{{ $review->rating }} out of 5 stars">{{ $review->starsLabel() }}</span>
                            <span class="agent-review-card__meta">{{ $review->maskedEmail() }} · {{ $review->created_at->format('M j, Y') }}</span>
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
