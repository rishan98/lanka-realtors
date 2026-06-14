@props(['listing'])

<article class="invest-listing-card">
    <a class="invest-listing-card__media" href="{{ route('listings.show', $listing) }}">
        <img
            class="invest-listing-card__image {{ $listing->cardImageClass() }}"
            src="{{ $listing->imageUrl() }}"
            alt="{{ $listing->title }}"
            loading="lazy"
            onerror="this.onerror=null;this.src='{{ \App\Models\Listing::defaultImageUrl() }}';this.classList.add('property-card__image--placeholder');"
        >
    </a>
    <div class="invest-listing-card__body">
        <div class="invest-listing-card__meta">{{ $listing->kindLabel() }} · {{ $listing->subtypeLabel() }}</div>
        <h2 class="invest-listing-card__title">
            <a href="{{ route('listings.show', $listing) }}">{{ $listing->title }}</a>
        </h2>
        @if($listing->bedrooms !== null || $listing->built_area_sqft)
            <div class="invest-listing-card__specs">
                @if($listing->bedrooms !== null)
                    {{ $listing->bedrooms >= 5 ? '5+ BHK' : $listing->bedrooms.' BHK' }}
                @endif
                @if($listing->built_area_sqft)
                    @if($listing->bedrooms !== null) · @endif{{ number_format($listing->built_area_sqft) }} sq.ft.
                @endif
            </div>
        @endif
        <div class="invest-listing-card__price">
            @if($listing->price)
                {{ $listing->currency }} {{ number_format($listing->price, 0) }}
            @else
                Price on request
            @endif
        </div>
        @if($listing->city)
            <div class="invest-listing-card__location">{{ $listing->city }}</div>
        @endif
    </div>
</article>
