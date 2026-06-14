@extends('layouts.portal')

@section('title', 'Owners — property listings by owners — '.config('app.name'))
@section('meta_description', 'Browse property ads posted directly by owners on Lanka Realtors — sales, rentals, investments, and wanted listings across Sri Lanka.')
@section('canonical', route('owners'))

@section('content')
<section class="section section--tight">
    <div class="container split">
        <aside class="refine-sidebar glass glass--refine">
            <header class="refine-sidebar__header">
                <span class="refine-sidebar__eyebrow">Owner listings</span>
                <h2 class="refine-sidebar__title">Refine</h2>
            </header>

            <form method="get" action="{{ route('owners') }}" class="refine-sidebar__filters">
                <div class="refine-field">
                    <label class="refine-field__label" for="owners-q">Keyword</label>
                    <div class="refine-field__control">
                        <span class="refine-field__icon" aria-hidden="true">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        </span>
                        <input class="input refine-field__input" id="owners-q" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search title or location">
                    </div>
                </div>
                <div class="refine-field">
                    <label class="refine-field__label" for="owners-city">City / area</label>
                    <div class="refine-field__control refine-field__control--select">
                        <span class="refine-field__icon" aria-hidden="true">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        </span>
                        <x-city-filter-select id="owners-city" name="city" :selected="$filters['city'] ?? ''" :districts="$districts" placeholder="All cities" />
                    </div>
                </div>
                @if(!empty($filters['kind']))
                    <input type="hidden" name="kind" value="{{ $filters['kind'] }}">
                @endif
                <button class="btn-gold refine-sidebar__submit" type="submit">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    Apply filters
                </button>
            </form>

            <div class="refine-sidebar__divider" aria-hidden="true"></div>
            <div class="refine-sidebar__types">
                <h3 class="refine-sidebar__types-label">Category</h3>
                <div class="refine-type-chips">
                    <a href="{{ route('owners', array_filter(['q' => $filters['q'] ?? null, 'city' => $filters['city'] ?? null])) }}"
                       class="refine-type-chip{{ empty($filters['kind']) ? ' is-active' : '' }}">All</a>
                    @foreach($kinds as $key => $meta)
                        <a href="{{ route('owners', array_filter(['kind' => $key, 'q' => $filters['q'] ?? null, 'city' => $filters['city'] ?? null])) }}"
                           class="refine-type-chip{{ ($filters['kind'] ?? null) === $key ? ' is-active' : '' }}">{{ $meta['nav_label'] ?? $meta['label'] }}</a>
                    @endforeach
                </div>
            </div>
        </aside>

        <div>
            <header class="mb-3">
                <h1 class="section-title">Owners</h1>
                <p class="section-lead">Only property ads posted by registered owners appear here — not agent listings.</p>
                <div class="row-flex mt-3">
                    @auth
                        <a class="btn-gold" href="{{ auth()->user()->postListingRoute() }}">Post your ad</a>
                    @else
                        <a class="btn-gold" href="{{ route('register', ['role' => 'owner']) }}">Register as owner</a>
                        <a class="pill" href="{{ route('login') }}">Sign in</a>
                    @endauth
                </div>
            </header>

            @if($listings->isEmpty())
                <div class="block-gray">No owner listings match your filters yet.</div>
            @else
                <p class="muted mb-3" style="font-size:0.92rem">{{ $listings->total() }} {{ \Illuminate\Support\Str::plural('listing', $listings->total()) }} from owners</p>
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
