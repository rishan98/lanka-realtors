@props(['listing'])

<a href="{{ route('listings.show', $listing) }}" class="modern-property-card">
    <div class="modern-property-card__image-wrap">
        <img
            class="modern-property-card__image {{ $listing->cardImageClass() }}"
            src="{{ $listing->imageUrl() }}"
            alt="{{ $listing->title }}"
            loading="lazy"
            onerror="this.onerror=null;this.src='{{ \App\Models\Listing::defaultImageUrl() }}';this.classList.add('property-card__image--placeholder');"
        >
        @if($listing->imageCount() > 0)
            <div class="modern-property-card__photo-count">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                {{ $listing->imageCount() }}
            </div>
        @endif
    </div>
    <div class="modern-property-card__content">
        <div class="modern-property-card__meta">{{ $listing->cardMetaLabel() }}</div>
        <div class="modern-property-card__price-row">
            @if($listing->price)
                {{ $listing->currency }} {{ number_format($listing->price, 0) }}
            @else
                POA
            @endif
            @if($area = $listing->cardAreaLabel())
                <span class="modern-property-card__price-divider">|</span>
                {{ $area }}
            @endif
        </div>
        <div class="modern-property-card__location">
            {{ $listing->city }}@if($listing->area), {{ $listing->area }}@endif
        </div>
        @if($status = $listing->cardStatusLabel())
            <div class="modern-property-card__status">{{ $status }}</div>
        @endif
        <span class="modern-property-card__cta">View {{ $listing->title }} listing</span>
    </div>
</a>
