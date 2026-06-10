@extends('layouts.portal')

@section('title', $seo['title'])
@section('meta_description', $seo['description'])
@section('canonical', $seo['canonical'])

@section('content')
<section class="section section--tight">
    <div class="container split">
        <aside class="refine-sidebar glass glass--refine">
            <header class="refine-sidebar__header">
                <span class="refine-sidebar__eyebrow">Search &amp; filter</span>
                <h2 class="refine-sidebar__title">Refine</h2>
            </header>

            <form method="get" action="{{ route('listings.index') }}" class="refine-sidebar__filters">
                <div class="refine-field">
                    <label class="refine-field__label" for="q2">Keyword</label>
                    <div class="refine-field__control">
                        <span class="refine-field__icon" aria-hidden="true">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        </span>
                        <input class="input refine-field__input" id="q2" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search title or location">
                    </div>
                </div>
                <div class="refine-field">
                    <label class="refine-field__label" for="city2">City / area</label>
                    <div class="refine-field__control refine-field__control--select">
                        <span class="refine-field__icon" aria-hidden="true">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        </span>
                        <x-city-filter-select id="city2" name="city" :selected="$filters['city'] ?? ''" :districts="$districts" placeholder="All cities" />
                    </div>
                </div>
                <button class="btn-gold refine-sidebar__submit" type="submit">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    Apply filters
                </button>
                @foreach(['kind', 'subtype', 'quick'] as $hidden)
                    @if(!empty($filters[$hidden]))
                        <input type="hidden" name="{{ $hidden }}" value="{{ $filters[$hidden] }}">
                    @endif
                @endforeach
            </form>

            @php($activeKind = $filters['kind'] ?? null)
            @if($activeKind && isset($kinds[$activeKind]))
                <div class="refine-sidebar__divider" aria-hidden="true"></div>
                <div class="refine-sidebar__types">
                    <h3 class="refine-sidebar__types-label">Types in this category</h3>
                    <div class="refine-type-chips">
                        @foreach($kinds[$activeKind]['subtypes'] as $subKey => $label)
                            <a href="{{ route('listings.index', ['kind' => $activeKind, 'subtype' => $subKey]) }}"
                               class="refine-type-chip{{ ($filters['subtype'] ?? null) === $subKey ? ' is-active' : '' }}">{{ $label }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>

        <div>
            <h1 class="section-title">Listings</h1>
            <p class="section-lead">Showing published properties. Use the sidebar to search and narrow by property type, or switch category from the menu above.</p>

            @if($listings->isEmpty())
                <div class="block-gray">No listings match your filters yet.</div>
            @else
                <div class="card-grid card-grid--listings">
                    @foreach($listings as $listing)
                        <article class="property-card">
                            <a href="{{ route('listings.show', $listing) }}">
                                <img class="property-card__image {{ $listing->cardImageClass() }}" src="{{ $listing->imageUrl() }}" alt="{{ $listing->title }}" loading="lazy" onerror="this.onerror=null;this.src='{{ \App\Models\Listing::defaultImageUrl() }}';this.classList.add('property-card__image--placeholder');">
                            </a>
                            <div class="property-card__body">
                                <div class="property-card__meta">{{ $listing->kindLabel() }} · {{ $listing->subtypeLabel() }}</div>
                                <h2 class="property-card__title">
                                    <a href="{{ route('listings.show', $listing) }}">{{ $listing->title }}</a>
                                </h2>
                                @if($listing->bedrooms !== null || $listing->built_area_sqft)
                                    <div class="muted" style="font-size:0.82rem;margin-bottom:6px">
                                        @if($listing->bedrooms !== null)
                                            {{ $listing->bedrooms >= 5 ? '5+ BHK' : $listing->bedrooms.' BHK' }}
                                        @endif
                                        @if($listing->built_area_sqft)
                                            @if($listing->bedrooms !== null) · @endif{{ number_format($listing->built_area_sqft) }} sq.ft.
                                        @endif
                                    </div>
                                @endif
                                <div class="property-card__price">
                                    @if($listing->price)
                                        {{ $listing->currency }} {{ number_format($listing->price, 0) }}
                                    @else
                                        Price on request
                                    @endif
                                </div>
                                @if($listing->city)
                                    <div class="muted mt-2" style="font-size:0.9rem">{{ $listing->city }}</div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-3">
                    {{ $listings->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
