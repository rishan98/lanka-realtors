@extends('layouts.portal')

@section('title', $seo['title'])
@section('meta_description', $seo['description'])
@section('canonical', $seo['canonical'])
@section('og_image', $seo['image'])

@section('content')
@php($totalPublished = array_sum($kindCounts))

<section class="agent-portfolio">
    <div class="agent-portfolio__bg" aria-hidden="true"></div>

    <div class="container agent-portfolio__container">
        <nav class="agent-portfolio__breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('portal.home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('find-realtor') }}">Agents</a>
            <span aria-hidden="true">/</span>
            <span>{{ $agent->name }}</span>
        </nav>

        <header class="agent-portfolio__hero agent-portfolio__hero--has-cover">
            <div class="agent-portfolio__cover{{ $agent->usesAvatarAsCover() ? ' agent-portfolio__cover--avatar' : '' }}">
                @if($agent->hasCover())
                    <img src="{{ $agent->coverDisplayUrl() }}" alt="" width="1440" height="400" loading="eager" decoding="async">
                @endif
            </div>

            <div class="agent-portfolio__hero-body glass">
            <div class="agent-portfolio__hero-top">
                <figure class="agent-portfolio__photo">
                    <img src="{{ $agent->avatarUrl() }}" alt="{{ $agent->name }}" width="120" height="120" loading="eager" decoding="async">
                </figure>

                <div class="agent-portfolio__hero-identity">
                    <div class="agent-portfolio__hero-headline">
                        <div>
                            <p class="agent-portfolio__eyebrow">
                                <span class="agent-portfolio__eyebrow-dot" aria-hidden="true"></span>
                                Agent portfolio
                            </p>
                            <h1 class="agent-portfolio__title">{{ $agent->name }}</h1>
                            <p class="agent-portfolio__company">{{ $agent->companyDisplayName() }}</p>
                        </div>

                        @if($agent->companyLogoUrl())
                            <img class="agent-portfolio__company-logo" src="{{ $agent->companyLogoUrl() }}" alt="{{ $agent->companyDisplayName() }}">
                        @endif
                    </div>

                    <ul class="agent-portfolio__badges">
                        @if($agent->is_preferred)
                            <li><span class="agent-portfolio__badge">LR Preferred</span></li>
                        @else
                            <li><span class="agent-portfolio__badge agent-portfolio__badge--muted">Verified agent</span></li>
                        @endif
                        @if($agent->hasRating())
                            <li><span class="agent-portfolio__badge agent-portfolio__badge--rating">{{ $agent->formattedRating() }} ★</span></li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="agent-portfolio__stats">
                <div class="agent-portfolio__stat">
                    <span class="agent-portfolio__stat-value">{{ $agent->published_sale_count ?? 0 }}</span>
                    <span class="agent-portfolio__stat-label">For sale</span>
                </div>
                <div class="agent-portfolio__stat">
                    <span class="agent-portfolio__stat-value">{{ $agent->published_rent_count ?? 0 }}</span>
                    <span class="agent-portfolio__stat-label">For rent</span>
                </div>
                <div class="agent-portfolio__stat">
                    <span class="agent-portfolio__stat-value">{{ $totalPublished }}</span>
                    <span class="agent-portfolio__stat-label">Total listings</span>
                </div>
                @if($agent->operating_since_year)
                    <div class="agent-portfolio__stat">
                        <span class="agent-portfolio__stat-value">{{ $agent->operating_since_year }}</span>
                        <span class="agent-portfolio__stat-label">Operating since</span>
                    </div>
                @endif
                @if($agent->buyers_served_estimate)
                    <div class="agent-portfolio__stat">
                        <span class="agent-portfolio__stat-value">{{ number_format($agent->buyers_served_estimate) }}+</span>
                        <span class="agent-portfolio__stat-label">Buyers served</span>
                    </div>
                @endif
            </div>

            @if($agent->phone || $agent->email)
                <div class="agent-portfolio__hero-contact"
                     data-agent-contact
                     data-lead-url="{{ route('agents.contact-leads.store', $agent) }}">
                    <span class="agent-portfolio__hero-contact-label">Contact {{ $agent->name }}</span>
                    <div class="agent-portfolio__contact-actions">
                        @if($agent->phone)
                            <div class="agent-portfolio__contact-reveal" data-contact-type="phone">
                                <button type="button" class="agent-portfolio__contact-btn agent-portfolio__contact-btn--phone">
                                    Show phone number
                                </button>
                                <div class="agent-portfolio__contact-reveal-result" hidden></div>
                            </div>
                        @endif
                        @if($agent->email)
                            <div class="agent-portfolio__contact-reveal" data-contact-type="email">
                                <button type="button" class="agent-portfolio__contact-btn agent-portfolio__contact-btn--email">
                                    Show email address
                                </button>
                                <div class="agent-portfolio__contact-reveal-result" hidden></div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            </div>
        </header>

        @if(!empty($agent->bio))
            <section class="agent-portfolio__bio glass glass--pad">
                <h2 class="agent-portfolio__bio-title">About {{ $agent->name }}</h2>
                <p class="agent-portfolio__bio-text">{{ $agent->bio }}</p>
            </section>
        @endif

        <section class="agent-portfolio__reviews glass" id="agent-reviews">
            <header class="agent-portfolio__reviews-head">
                <div>
                    <h2 class="agent-portfolio__reviews-title">Reviews</h2>
                    <p class="agent-portfolio__reviews-lead" id="agent-reviews-summary">
                        @if($reviewCount > 0)
                            {{ number_format($averageReviewRating, 1) }} average from {{ $reviewCount }} {{ \Illuminate\Support\Str::plural('review', $reviewCount) }}
                        @else
                            No reviews yet. Be the first to share your experience.
                        @endif
                    </p>
                </div>
                <button type="button" class="btn-gold agent-portfolio__reviews-add" data-review-open>
                    Add a review
                </button>
            </header>

            <div class="agent-portfolio__reviews-list" id="agent-reviews-list">
                @forelse($visibleReviews as $review)
                    @include('portal.partials.agent-review-card', ['review' => $review])
                @empty
                    <p class="agent-portfolio__reviews-empty muted mb-0" id="agent-reviews-empty">Reviews from buyers and renters will appear here.</p>
                @endforelse
            </div>

            @if($reviewCount > 3)
                <div class="agent-portfolio__reviews-more">
                    <button type="button" class="pill agent-portfolio__reviews-all-btn" data-reviews-all-open>
                        View all {{ $reviewCount }} reviews
                    </button>
                </div>
            @endif
        </section>

        <div class="portfolio-modal portfolio-modal--reviews" id="agent-reviews-all-modal" hidden aria-hidden="true">
            <div class="portfolio-modal__backdrop" data-reviews-all-close tabindex="-1"></div>
            <div class="portfolio-modal__dialog portfolio-modal__dialog--reviews" role="dialog" aria-modal="true" aria-labelledby="agent-reviews-all-title">
                <button type="button" class="portfolio-modal__close" data-reviews-all-close aria-label="Close all reviews">&times;</button>
                <h2 class="portfolio-modal__title" id="agent-reviews-all-title">All reviews for {{ $agent->name }}</h2>
                <p class="portfolio-modal__lead muted">{{ number_format($averageReviewRating, 1) }} average from {{ $reviewCount }} {{ \Illuminate\Support\Str::plural('review', $reviewCount) }}</p>
                <div class="agent-portfolio__reviews-list agent-portfolio__reviews-list--modal">
                    @foreach($reviews as $review)
                        @include('portal.partials.agent-review-card', ['review' => $review])
                    @endforeach
                </div>
                <div class="portfolio-modal__actions">
                    <button type="button" class="btn-gold" data-reviews-all-close>Close</button>
                </div>
            </div>
        </div>

        <div class="portfolio-modal" id="agent-review-modal" hidden aria-hidden="true">
            <div class="portfolio-modal__backdrop" data-review-close tabindex="-1"></div>
            <div class="portfolio-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="agent-review-modal-title">
                <button type="button" class="portfolio-modal__close" data-review-close aria-label="Close review form">&times;</button>
                <h2 class="portfolio-modal__title" id="agent-review-modal-title">Add a review for {{ $agent->name }}</h2>
                <p class="portfolio-modal__lead muted">Share your experience working with this agent. Your review will be sent to the agent for approval before it appears here.</p>

                <form class="agent-review-form" id="agent-review-form" novalidate>
                    @csrf
                    <div class="field">
                        <label for="review-email">Your email</label>
                        <input id="review-email" type="email" name="email" class="input" required autocomplete="email" value="{{ auth()->check() && auth()->id() !== $agent->id ? auth()->user()->email : '' }}">
                        <div class="error-text" data-review-error="email" hidden></div>
                    </div>

                    <div class="field mt-2">
                        <label>Your rating</label>
                        <div class="star-rating" role="radiogroup" aria-label="Star rating">
                            @for($star = 5; $star >= 1; $star--)
                                <input class="star-rating__input" type="radio" name="rating" id="review-star-{{ $star }}" value="{{ $star }}" required>
                                <label class="star-rating__star" for="review-star-{{ $star }}" title="{{ $star }} star{{ $star === 1 ? '' : 's' }}">★</label>
                            @endfor
                        </div>
                        <div class="error-text" data-review-error="rating" hidden></div>
                    </div>

                    <div class="field mt-2">
                        <label for="review-message">Your message</label>
                        <textarea id="review-message" name="message" class="input" rows="4" required minlength="10" maxlength="2000" placeholder="Tell others about your experience…"></textarea>
                        <div class="error-text" data-review-error="message" hidden></div>
                    </div>

                    <div class="error-text" data-review-error="form" hidden></div>
                    <div class="portfolio-modal__actions">
                        <button type="button" class="pill" data-review-close>Cancel</button>
                        <button type="submit" class="btn-gold" id="agent-review-submit">Submit review</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="portfolio-modal portfolio-modal--notice" id="agent-review-notice-modal" hidden aria-hidden="true">
            <div class="portfolio-modal__backdrop" data-review-notice-close tabindex="-1"></div>
            <div class="portfolio-modal__dialog portfolio-modal__dialog--notice" role="dialog" aria-modal="true" aria-labelledby="agent-review-notice-title">
                <div class="portfolio-modal__notice-icon" id="agent-review-notice-icon" aria-hidden="true">✓</div>
                <h2 class="portfolio-modal__title portfolio-modal__title--center" id="agent-review-notice-title">Review submitted</h2>
                <p class="portfolio-modal__notice-message" id="agent-review-notice-message"></p>
                <div class="portfolio-modal__actions portfolio-modal__actions--center">
                    <button type="button" class="btn-gold" data-review-notice-close>OK</button>
                </div>
            </div>
        </div>

        <section class="agent-portfolio__listings">
            <header class="agent-portfolio__listings-head">
                <div>
                    <h2 class="agent-portfolio__listings-title">Listings</h2>
                    <p class="agent-portfolio__listings-lead">Browse published properties from this agent.</p>
                </div>
                <span class="agent-portfolio__listings-count">
                    {{ $listings->total() }} {{ \Illuminate\Support\Str::plural('result', $listings->total()) }}
                </span>
            </header>

            <nav class="agent-portfolio__filters" aria-label="Filter by listing type">
                <a href="{{ route('agents.portfolio', $agent) }}"
                   class="agent-portfolio__filter{{ empty($activeKind) ? ' is-active' : '' }}">
                    All
                    @if($totalPublished > 0)
                        <span class="agent-portfolio__filter-count">{{ $totalPublished }}</span>
                    @endif
                </a>
                @foreach($portfolioKinds as $kind)
                    @php($meta = config('listing.kinds.'.$kind))
                    <a href="{{ route('agents.portfolio', ['agent' => $agent, 'kind' => $kind]) }}"
                       class="agent-portfolio__filter{{ $activeKind === $kind ? ' is-active' : '' }}">
                        {{ $meta['nav_label'] ?? $meta['label'] }}
                        @if(($kindCounts[$kind] ?? 0) > 0)
                            <span class="agent-portfolio__filter-count">{{ $kindCounts[$kind] }}</span>
                        @endif
                    </a>
                @endforeach
            </nav>

            @if($listings->isEmpty())
                <div class="agent-portfolio__empty">
                    <p class="agent-portfolio__empty-title">No listings to show</p>
                    <p class="muted mb-0">
                        @if($activeKind)
                            No published {{ strtolower(config('listing.kinds.'.$activeKind.'.label', $activeKind)) }} ads from this agent yet.
                        @else
                            No published ads from this agent yet.
                        @endif
                    </p>
                    @if($activeKind)
                        <a class="btn-gold agent-portfolio__empty-btn" href="{{ route('agents.portfolio', $agent) }}">View all listings</a>
                    @endif
                </div>
            @else
                <div class="card-grid card-grid--listings agent-portfolio__grid">
                    @foreach($listings as $listing)
                        <article class="property-card">
                            <a href="{{ route('listings.show', $listing) }}">
                                <img class="property-card__image {{ $listing->cardImageClass() }}" src="{{ $listing->imageUrl() }}" alt="{{ $listing->title }}" loading="lazy" onerror="this.onerror=null;this.src='{{ \App\Models\Listing::defaultImageUrl() }}';this.classList.add('property-card__image--placeholder');">
                            </a>
                            <div class="property-card__body">
                                <div class="property-card__meta">{{ $listing->kindLabel() }} · {{ $listing->subtypeLabel() }}</div>
                                <h3 class="property-card__title">
                                    <a href="{{ route('listings.show', $listing) }}">{{ $listing->title }}</a>
                                </h3>
                                <div class="property-card__price">
                                    @if($listing->price)
                                        {{ $listing->currency }} {{ number_format($listing->price, 0) }}
                                    @else
                                        Price on request
                                    @endif
                                </div>
                                @if($listing->city)
                                    <div class="muted mt-2">{{ $listing->city }}</div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                @if($listings->hasPages())
                    <div class="agent-portfolio__pagination">
                        {{ $listings->links() }}
                    </div>
                @endif
            @endif
        </section>
    </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
    var root = document.querySelector('[data-agent-contact]');
    if (root) {
        var leadUrl = root.getAttribute('data-lead-url');
        var csrf = document.querySelector('meta[name="csrf-token"]');
        var token = csrf ? csrf.getAttribute('content') : '';

        root.querySelectorAll('[data-contact-type]').forEach(function (block) {
            var btn = block.querySelector('.agent-portfolio__contact-btn');
            var result = block.querySelector('.agent-portfolio__contact-reveal-result');
            if (!btn || !result) return;

            btn.addEventListener('click', function () {
                if (btn.disabled) return;

                var type = block.getAttribute('data-contact-type');
                btn.disabled = true;
                btn.textContent = 'Loading…';

                fetch(leadUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ type: type })
                })
                    .then(function (response) {
                        if (!response.ok) throw new Error('Request failed');
                        return response.json();
                    })
                    .then(function (data) {
                        btn.hidden = true;
                        result.hidden = false;
                        result.replaceChildren();

                        var label = document.createElement('span');
                        label.className = 'agent-portfolio__contact-reveal-label';
                        label.textContent = data.type === 'phone' ? 'Phone' : 'Email';

                        var value = document.createElement('span');
                        value.className = 'agent-portfolio__contact-reveal-value';
                        value.textContent = data.value;

                        var action = document.createElement('a');
                        action.className = 'agent-portfolio__contact-btn agent-portfolio__contact-btn--' + data.type;
                        action.href = data.href;
                        action.textContent = data.action_label;

                        result.append(label, value, action);
                    })
                    .catch(function () {
                        btn.disabled = false;
                        btn.textContent = type === 'phone' ? 'Show phone number' : 'Show email address';
                        alert('Could not load contact details. Please try again.');
                    });
            });
        });
    }

    var modal = document.getElementById('agent-review-modal');
    var form = document.getElementById('agent-review-form');
    var noticeModal = document.getElementById('agent-review-notice-modal');
    var allReviewsModal = document.getElementById('agent-reviews-all-modal');
    if (!modal || !form || !noticeModal) return;

    var reviewUrl = @json(route('agents.reviews.store', $agent));
    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
    var submitBtn = document.getElementById('agent-review-submit');
    var noticeTitle = document.getElementById('agent-review-notice-title');
    var noticeMessage = document.getElementById('agent-review-notice-message');
    var noticeIcon = document.getElementById('agent-review-notice-icon');
    var lastFocused = null;

    function isAnyModalOpen() {
        return !modal.hidden
            || !noticeModal.hidden
            || (allReviewsModal && !allReviewsModal.hidden);
    }

    function setBodyModalLock() {
        document.body.classList.toggle('portfolio-modal-open', isAnyModalOpen());
    }

    function restoreFocusIfNoModal() {
        if (!isAnyModalOpen() && lastFocused) lastFocused.focus();
    }

    function clearReviewErrors() {
        form.querySelectorAll('[data-review-error]').forEach(function (el) {
            el.hidden = true;
            el.textContent = '';
        });
    }

    function showReviewErrors(errors) {
        Object.keys(errors).forEach(function (key) {
            var el = form.querySelector('[data-review-error="' + key + '"]');
            if (el && errors[key] && errors[key][0]) {
                el.textContent = errors[key][0];
                el.hidden = false;
            }
        });
    }

    function openReviewModal() {
        lastFocused = document.activeElement;
        modal.hidden = false;
        modal.setAttribute('aria-hidden', 'false');
        setBodyModalLock();
        var emailInput = document.getElementById('review-email');
        if (emailInput) emailInput.focus();
    }

    function closeReviewModal() {
        modal.hidden = true;
        modal.setAttribute('aria-hidden', 'true');
        clearReviewErrors();
        setBodyModalLock();
        restoreFocusIfNoModal();
    }

    function openAllReviewsModal() {
        if (!allReviewsModal) return;
        lastFocused = document.activeElement;
        allReviewsModal.hidden = false;
        allReviewsModal.setAttribute('aria-hidden', 'false');
        setBodyModalLock();
        var closeBtn = allReviewsModal.querySelector('[data-reviews-all-close].portfolio-modal__close');
        if (closeBtn) closeBtn.focus();
    }

    function closeAllReviewsModal() {
        if (!allReviewsModal) return;
        allReviewsModal.hidden = true;
        allReviewsModal.setAttribute('aria-hidden', 'true');
        setBodyModalLock();
        restoreFocusIfNoModal();
    }

    function openNoticeModal(options) {
        var isError = !!options.error;

        noticeModal.classList.toggle('portfolio-modal--error', isError);
        noticeTitle.textContent = options.title || (isError ? 'Something went wrong' : 'Review submitted');
        noticeMessage.textContent = options.message || '';
        noticeIcon.textContent = isError ? '!' : '✓';

        noticeModal.hidden = false;
        noticeModal.setAttribute('aria-hidden', 'false');
        setBodyModalLock();

        var okBtn = noticeModal.querySelector('[data-review-notice-close].btn-gold, [data-review-notice-close]');
        if (okBtn) okBtn.focus();
    }

    function closeNoticeModal() {
        noticeModal.hidden = true;
        noticeModal.setAttribute('aria-hidden', 'true');
        setBodyModalLock();
        restoreFocusIfNoModal();
    }

    document.querySelectorAll('[data-review-open]').forEach(function (btn) {
        btn.addEventListener('click', openReviewModal);
    });

    document.querySelectorAll('[data-reviews-all-open]').forEach(function (btn) {
        btn.addEventListener('click', openAllReviewsModal);
    });

    if (allReviewsModal) {
        allReviewsModal.querySelectorAll('[data-reviews-all-close]').forEach(function (el) {
            el.addEventListener('click', closeAllReviewsModal);
        });
    }

    modal.querySelectorAll('[data-review-close]').forEach(function (el) {
        el.addEventListener('click', closeReviewModal);
    });

    noticeModal.querySelectorAll('[data-review-notice-close]').forEach(function (el) {
        el.addEventListener('click', closeNoticeModal);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') return;
        if (!noticeModal.hidden) {
            closeNoticeModal();
            return;
        }
        if (allReviewsModal && !allReviewsModal.hidden) {
            closeAllReviewsModal();
            return;
        }
        if (!modal.hidden) closeReviewModal();
    });

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        clearReviewErrors();

        if (!form.reportValidity()) return;

        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting…';

        var formData = new FormData(form);
        var payload = {
            email: formData.get('email'),
            message: formData.get('message'),
            rating: parseInt(formData.get('rating'), 10)
        };

        fetch(reviewUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        })
            .then(function (response) {
                return response.json().then(function (data) {
                    if (!response.ok) throw { status: response.status, data: data };
                    return data;
                });
            })
            .then(function (data) {
                form.reset();
                closeReviewModal();
                openNoticeModal({
                    title: 'Review submitted',
                    message: data.message || 'Thank you for your review. It will appear on this portfolio after the agent approves it.'
                });
            })
            .catch(function (error) {
                if (error.status === 422 && error.data) {
                    if (error.data.errors) {
                        showReviewErrors(error.data.errors);
                    } else if (error.data.message) {
                        var formError = form.querySelector('[data-review-error="form"]');
                        if (formError) {
                            formError.textContent = error.data.message;
                            formError.hidden = false;
                        }
                    }
                    return;
                }
                openNoticeModal({
                    error: true,
                    title: 'Could not submit review',
                    message: 'Please try again in a moment.'
                });
            })
            .finally(function () {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit review';
            });
    });
})();
</script>
@endpush
