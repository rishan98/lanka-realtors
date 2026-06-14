<article class="agent-portfolio__review">
    <header class="agent-portfolio__review-head">
        <div>
            <span class="agent-portfolio__review-stars" aria-label="{{ $review->rating }} out of 5 stars">{{ $review->starsLabel() }}</span>
            <span class="agent-portfolio__review-author">{{ $review->maskedEmail() }}</span>
        </div>
        <time class="agent-portfolio__review-date" datetime="{{ $review->created_at->toDateString() }}">{{ $review->created_at->format('M j, Y') }}</time>
    </header>
    <p class="agent-portfolio__review-message">{{ $review->message }}</p>
</article>
