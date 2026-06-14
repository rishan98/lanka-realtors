@extends('layouts.portal')

@section('title', $seo['title'])
@section('meta_description', $seo['description'])
@section('canonical', $seo['canonical'])
@section('og_image', $seo['image'])
@section('og_type', 'product')

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'RealEstateListing',
    'name' => $listing->title,
    'description' => \Illuminate\Support\Str::limit(strip_tags((string) $listing->description), 300, ''),
    'url' => route('listings.show', $listing),
    'image' => $listing->imageUrl(),
    'datePosted' => $listing->created_at->toAtomString(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
@php
    $images = $listing->imageUrls();
    if (empty($images)) {
        $images = [$listing->imageUrl()];
    }
    $contactPhone = $listing->contactPhone();
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
                    <h1 class="listing-detail__title">{{ $listing->title }}</h1>
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

            </div>

            <aside class="listing-detail__aside">
                @if($listing->user->isAgent())
                    <div class="listing-detail__aside-agent">
                        <x-agent-card :agent="$listing->user" />
                    </div>
                @endif
                <div class="listing-detail__contact-card">
                    <div class="listing-detail__price listing-detail__price--desktop">{{ $listing->formattedPriceDisplay() }}</div>
                    @if($perSqft = $listing->pricePerSqftLabel())
                        <p class="listing-detail__price-note">{{ $perSqft }}</p>
                    @endif

                    <h3 class="listing-detail__contact-title">Contact {{ $listing->user->isAgent() ? 'Agent' : 'Owner' }}</h3>

                    <div class="listing-detail__contact-actions"
                         data-listing-contact
                         data-lead-url="{{ route('listings.contact-leads.store', $listing) }}">
                        @if($contactPhone)
                            <div class="listing-detail__contact-block" data-contact-type="phone">
                                <button type="button"
                                        class="listing-detail__contact-btn listing-detail__contact-btn--primary">
                                    Get Phone No.
                                </button>
                                <p class="listing-detail__contact-number">{{ \App\Support\PhoneHelper::mask($contactPhone) }}</p>
                                <div class="listing-detail__contact-reveal" hidden></div>
                            </div>
                        @endif

                        <div class="listing-detail__contact-block" data-contact-type="email">
                            <button type="button"
                                    class="listing-detail__contact-btn listing-detail__contact-btn--outline">
                                Email {{ $listing->user->isAgent() ? 'Agent' : 'Owner' }}
                            </button>
                            <div class="listing-detail__contact-reveal" hidden></div>
                        </div>
                    </div>

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

        <section class="listing-detail__similar">
            <header class="listing-detail__similar-header">
                <h2 class="listing-detail__section-title">Similar properties</h2>
                <a href="{{ route('listings.index', array_filter(['kind' => $listing->listing_kind, 'city' => $listing->city, 'subtype' => $listing->property_subtype])) }}" class="listing-detail__similar-link">View all &rarr;</a>
            </header>

            @if($similarListings->isEmpty())
                <p class="listing-detail__similar-empty muted mb-0">No similar listings right now. <a href="{{ route('listings.index', ['kind' => $listing->listing_kind]) }}">Browse more {{ $listing->kindLabel() }} properties</a>.</p>
            @else
                <div class="modern-property-grid">
                    @foreach($similarListings as $similar)
                        <x-modern-property-card :listing="$similar" />
                    @endforeach
                </div>
            @endif
        </section>
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

    var contactRoot = document.querySelector('[data-listing-contact]');
    if (contactRoot) {
        var leadUrl = contactRoot.getAttribute('data-lead-url');
        var csrf = document.querySelector('meta[name="csrf-token"]');
        var token = csrf ? csrf.getAttribute('content') : '';

        contactRoot.querySelectorAll('[data-contact-type]').forEach(function (block) {
            var type = block.getAttribute('data-contact-type');
            var btn = block.querySelector('.listing-detail__contact-btn');
            var masked = block.querySelector('.listing-detail__contact-number');
            var result = block.querySelector('.listing-detail__contact-reveal');
            if (!btn || !result) return;

            btn.addEventListener('click', function () {
                if (btn.disabled) return;

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
                        if (masked) {
                            masked.hidden = true;
                        }
                        result.hidden = false;
                        result.replaceChildren();

                        if (type === 'phone') {
                            var number = document.createElement('p');
                            number.className = 'listing-detail__contact-number';
                            number.textContent = data.value;
                            result.appendChild(number);
                        } else {
                            var email = document.createElement('p');
                            email.className = 'listing-detail__contact-number';
                            email.textContent = data.value;
                            result.appendChild(email);
                        }

                        var action = document.createElement('a');
                        action.className = 'listing-detail__contact-btn listing-detail__contact-btn--' + (type === 'phone' ? 'primary' : 'outline');
                        action.href = data.href;
                        action.textContent = data.action_label;
                        result.appendChild(action);
                    })
                    .catch(function () {
                        btn.disabled = false;
                        btn.textContent = type === 'phone' ? 'Get Phone No.' : 'Email {{ $listing->user->isAgent() ? 'Agent' : 'Owner' }}';
                        alert('Could not load contact details. Please try again.');
                    });
            });
        });
    }
})();
</script>
@endpush
