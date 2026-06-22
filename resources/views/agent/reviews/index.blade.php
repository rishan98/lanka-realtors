@extends('layouts.agent')

@section('title', 'Portfolio reviews — '.config('app.name'))

@section('agent_main')
<header class="agent-page-head">
    <div class="row-flex" style="justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
        <div>
            <h1>Portfolio reviews</h1>
            <p>Reviews submitted for your portfolio. An administrator approves them before they are published.</p>
        </div>
        <a class="pill" href="{{ route('agents.portfolio', auth()->user()) }}" target="_blank" rel="noopener">View portfolio</a>
    </div>
</header>

<div class="agent-stats">
    <article class="agent-stat">
        <div class="agent-stat__label">Awaiting admin approval</div>
        <div class="agent-stat__value">{{ $pendingCount }}</div>
    </article>
    <article class="agent-stat">
        <div class="agent-stat__label">Published reviews</div>
        <div class="agent-stat__value">{{ $approvedCount }}</div>
    </article>
</div>

@if($pendingReviews->isNotEmpty())
    <section class="agent-panel">
        <div class="agent-panel__head">
            <h2>Awaiting admin approval</h2>
        </div>
        <div class="agent-review-queue">
            @foreach($pendingReviews as $review)
                <article class="agent-review-card agent-review-card--pending">
                    <header class="agent-review-card__head">
                        <div>
                            <span class="agent-review-card__stars" aria-label="{{ $review->rating }} out of 5 stars">{{ $review->starsLabel() }}</span>
                            <span class="agent-review-card__meta">{{ $review->maskedEmail() }} · {{ $review->created_at->format('M j, Y g:i A') }}</span>
                        </div>
                        <span class="agent-review-card__badge agent-review-card__badge--pending">Pending admin approval</span>
                    </header>
                    <p class="agent-review-card__message">{{ $review->message }}</p>
                </article>
            @endforeach
        </div>
    </section>
@endif

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
@elseif($pendingReviews->isEmpty())
    <section class="agent-panel">
        <p class="mb-0" style="color:#5a6578">No reviews yet.</p>
    </section>
@endif

@if($rejectedReviews->isNotEmpty())
    <section class="agent-panel">
        <div class="agent-panel__head">
            <h2>Not published</h2>
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
