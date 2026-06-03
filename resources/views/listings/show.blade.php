@extends('layouts.portal')

@section('title', $listing->displayHeadline().' — '.config('app.name'))

@section('content')
@php
    $images = $listing->imageUrls();
    if (empty($images)) {
        $images = [$listing->imageUrl()];
    }
    $contactPhone = $listing->contact_number ?: $listing->user->phone;
@endphp

<section class="listing-detail">
    <div class="container">
        <nav class="listing-detail__breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('portal.home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('listings.index', ['kind' => $listing->listing_kind]) }}">{{ $listing->kindLabel() }}</a>
            @if($listing->city)
                <span aria-hidden="true">/</span>
                <a href="{{ route('listings.index', ['kind' => $listing->listing_kind, 'city' => $listing->city]) }}">{{ $listing->city }}</a>
            @endif
        </nav>

        <div class="listing-detail__top-meta">
            <span>Posted on: {{ $listing->created_at->format('M d, Y') }}</span>
            <span>Property ID: {{ str_pad((string) $listing->id, 8, '0', STR_PAD_LEFT) }}</span>
        </div>

        <div class="listing-detail__layout">
            <div class="listing-detail__main">
                <div class="listing-detail__head">
                    <div class="listing-detail__price listing-detail__price--mobile">{{ $listing->formattedPriceDisplay() }}</div>
                    <h1 class="listing-detail__title">{{ $listing->displayHeadline() }}</h1>
                    @if($listing->area || $listing->city)
                        <p class="listing-detail__location">
                            @if($listing->area){{ $listing->area }}@endif
                            @if($listing->area && $listing->city), @endif
                            @if($listing->city){{ $listing->city }}@endif
                        </p>
                    @endif
                </div>

                <div class="listing-detail__gallery" data-listing-gallery>
                    <div class="listing-detail__gallery-main">
                        <img src="{{ $images[0] }}" alt="{{ $listing->title }}" id="listing-gallery-main" class="listing-detail__gallery-image">
                        <span class="listing-detail__photo-count">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                            {{ count($images) }} Photo{{ count($images) === 1 ? '' : 's' }}
                        </span>
                    </div>
                    @if(count($images) > 1)
                        <div class="listing-detail__gallery-thumbs">
                            @foreach($images as $index => $url)
                                <button type="button"
                                        class="listing-detail__thumb{{ $index === 0 ? ' is-active' : '' }}"
                                        data-gallery-src="{{ $url }}"
                                        aria-label="View photo {{ $index + 1 }}">
                                    <img src="{{ $url }}" alt="">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="listing-detail__overview">
                    @foreach($listing->overviewItems() as $item)
                        <div class="listing-detail__overview-item">
                            <span class="listing-detail__overview-label">{{ $item['label'] }}</span>
                            <span class="listing-detail__overview-value">{{ $item['value'] }}</span>
                        </div>
                    @endforeach
                </div>

                @if($listing->detailFacts())
                    <section class="listing-detail__section">
                        <h2 class="listing-detail__section-title">More Details</h2>
                        <div class="listing-detail__facts">
                            @foreach($listing->detailFacts() as $fact)
                                <div class="listing-detail__fact">
                                    <span class="listing-detail__fact-label">{{ $fact['label'] }}</span>
                                    <span class="listing-detail__fact-value">{{ $fact['value'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if($listing->description)
                    <section class="listing-detail__section">
                        <h2 class="listing-detail__section-title">Description</h2>
                        <div class="listing-detail__description" id="listing-description">
                            {!! nl2br(e($listing->description)) !!}
                        </div>
                        @if(strlen(strip_tags($listing->description)) > 280)
                            <button type="button" class="listing-detail__read-more" data-read-more="listing-description">Read more</button>
                        @endif
                    </section>
                @endif

                @if($listing->latitude && $listing->longitude)
                    <section class="listing-detail__section">
                        <h2 class="listing-detail__section-title">Location</h2>
                        <p class="listing-detail__map-link">
                            <a href="{{ route('locate') }}?lat={{ $listing->latitude }}&lng={{ $listing->longitude }}" class="btn-gold btn-gold--sm">View on map</a>
                        </p>
                    </section>
                @endif

                @if($listing->user->isAgent())
                    <section class="listing-detail__section listing-detail__section--agent">
                        <h2 class="listing-detail__section-title">About Agent</h2>
                        <div class="listing-detail__agent-card">
                            <x-agent-card :agent="$listing->user" />
                        </div>
                    </section>
                @endif
            </div>

            <aside class="listing-detail__aside">
                <div class="listing-detail__contact-card">
                    <div class="listing-detail__price listing-detail__price--desktop">{{ $listing->formattedPriceDisplay() }}</div>
                    @if($perSqft = $listing->pricePerSqftLabel())
                        <p class="listing-detail__price-note">{{ $perSqft }}</p>
                    @endif

                    <h3 class="listing-detail__contact-title">Contact {{ $listing->user->isAgent() ? 'Agent' : 'Owner' }}</h3>

                    @if($contactPhone)
                        <button type="button"
                                class="listing-detail__contact-btn listing-detail__contact-btn--primary"
                                id="listing-reveal-phone"
                                data-phone="{{ $contactPhone }}">
                            Get Phone No.
                        </button>
                        <p class="listing-detail__contact-number" id="listing-phone-display">{{ \App\Support\PhoneHelper::mask($contactPhone) }}</p>
                    @endif

                    <a href="mailto:{{ $listing->user->email }}" class="listing-detail__contact-btn listing-detail__contact-btn--outline">
                        Email {{ $listing->user->isAgent() ? 'Agent' : 'Owner' }}
                    </a>

                    @unless($listing->user->isAgent())
                        <div class="listing-detail__seller">
                            <img src="{{ $listing->user->avatarUrl() }}" alt="" class="listing-detail__seller-photo">
                            <div>
                                <div class="listing-detail__seller-name">{{ $listing->user->name }}</div>
                                <div class="listing-detail__seller-agency">Property owner</div>
                            </div>
                        </div>
                    @endunless
                </div>
            </aside>
        </div>

        @if($similarListings->isNotEmpty())
            <section class="listing-detail__similar">
                <header class="listing-detail__similar-header">
                    <h2 class="listing-detail__section-title">Similar properties nearby</h2>
                    <a href="{{ route('listings.index', array_filter(['kind' => $listing->listing_kind, 'city' => $listing->city])) }}" class="listing-detail__similar-link">View all &rarr;</a>
                </header>
                <div class="modern-property-grid">
                    @foreach($similarListings as $similar)
                        <a href="{{ route('listings.show', $similar) }}" class="modern-property-card">
                            <div class="modern-property-card__image-wrap">
                                <img class="modern-property-card__image {{ $similar->cardImageClass() }}" src="{{ $similar->imageUrl() }}" alt="{{ $similar->title }}" loading="lazy" onerror="this.onerror=null;this.src='{{ \App\Models\Listing::defaultImageUrl() }}';this.classList.add('property-card__image--placeholder');">
                            </div>
                            <div class="modern-property-card__content">
                                <div class="modern-property-card__meta">
                                    {{ $similar->bedrooms ? $similar->bedrooms.' BHK ' : '' }}{{ $similar->subtypeLabel() }}
                                </div>
                                <div class="modern-property-card__price-row">
                                    @if($similar->price)
                                        {{ $similar->currency }} {{ number_format($similar->price, 0) }}
                                    @else
                                        POA
                                    @endif
                                </div>
                                <div class="modern-property-card__location">
                                    {{ $similar->city }}@if($similar->area), {{ $similar->area }}@endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
    var gallery = document.querySelector('[data-listing-gallery]');
    if (gallery) {
        var main = document.getElementById('listing-gallery-main');
        gallery.querySelectorAll('[data-gallery-src]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (!main) return;
                main.src = btn.getAttribute('data-gallery-src');
                gallery.querySelectorAll('.listing-detail__thumb').forEach(function (t) {
                    t.classList.remove('is-active');
                });
                btn.classList.add('is-active');
            });
        });
    }

    document.querySelectorAll('[data-read-more]').forEach(function (btn) {
        var target = document.getElementById(btn.getAttribute('data-read-more'));
        if (!target) return;
        target.classList.add('is-collapsed');
        btn.addEventListener('click', function () {
            var expanded = target.classList.toggle('is-expanded');
            target.classList.toggle('is-collapsed', !expanded);
            btn.textContent = expanded ? 'Read less' : 'Read more';
        });
    });

    var revealBtn = document.getElementById('listing-reveal-phone');
    var phoneDisplay = document.getElementById('listing-phone-display');
    if (revealBtn && phoneDisplay) {
        revealBtn.addEventListener('click', function () {
            var phone = revealBtn.getAttribute('data-phone');
            if (!phone) return;
            phoneDisplay.textContent = phone;
            var link = document.createElement('a');
            link.href = 'tel:' + phone.replace(/\s+/g, '');
            link.className = revealBtn.className;
            link.textContent = 'Call now';
            revealBtn.replaceWith(link);
        });
    }
})();
</script>
@endpush
