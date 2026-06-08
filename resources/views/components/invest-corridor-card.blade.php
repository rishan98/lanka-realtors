@props(['listing'])

@php($secondary = $listing->investSecondaryStat())
@php($highlights = $listing->investHighlightLines())

<a href="{{ route('listings.show', $listing) }}" class="mb-corridor-card">
    <img
        src="{{ $listing->imageUrl() }}"
        class="mb-corridor-card__image {{ $listing->cardImageClass() }}"
        alt="{{ $listing->title }}"
        loading="lazy"
        onerror="this.onerror=null;this.src='{{ \App\Models\Listing::defaultImageUrl() }}';this.classList.add('property-card__image--placeholder');"
    >
    <div class="mb-corridor-card__body">
        <h3 class="mb-corridor-card__title">{{ $listing->title }}</h3>
        @if($highlights !== [])
            <ul class="mb-corridor-card__list">
                @foreach($highlights as $point)
                    <li class="mb-corridor-card__list-item">
                        <svg class="mb-corridor-card__list-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                        {{ $point }}
                    </li>
                @endforeach
            </ul>
        @endif
        <div class="mb-corridor-card__footer">
            <div class="mb-corridor-card__stat">
                <span class="mb-corridor-card__stat-value">{{ $listing->priceShortLabel() }}</span>
                <span class="mb-corridor-card__stat-label">{{ $listing->investPriceFootnote() }}</span>
            </div>
            <div class="mb-corridor-card__stat">
                <span class="mb-corridor-card__stat-value">{{ $secondary['value'] }}</span>
                <span class="mb-corridor-card__stat-label">{{ $secondary['label'] }}</span>
            </div>
        </div>
    </div>
</a>
