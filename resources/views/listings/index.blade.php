@extends('layouts.portal')

@section('title', $seo['title'])
@section('meta_description', $seo['description'])
@section('canonical', $seo['canonical'])

@section('content')
<section class="section section--tight">
    <div class="container split">
        <aside class="glass glass--pad">
            <h2 class="section-title" style="font-size:1.1rem">Refine</h2>

            @php($activeKind = $filters['kind'] ?? null)
            @if($activeKind && isset($kinds[$activeKind]))
                <div class="mt-2">
                    <div class="field-label">Types in this category</div>
                    <div class="subtype-list">
                        @foreach($kinds[$activeKind]['subtypes'] as $subKey => $label)
                            <a href="{{ route('listings.index', ['kind' => $activeKind, 'subtype' => $subKey]) }}"
                               class="{{ ($filters['subtype'] ?? null) === $subKey ? 'is-active' : '' }}">{{ $label }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>

        <div>
            <h1 class="section-title">Listings</h1>
            <p class="section-lead">Showing published properties. Use the panel to narrow by property type, or switch category from the menu above.</p>

            <form method="get" action="{{ route('listings.index') }}" class="glass glass--pad mt-2" style="margin-bottom:22px">
                <div class="search-row">
                    <div class="field">
                        <label for="q2">Keyword</label>
                        <input class="input" id="q2" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search title or location">
                    </div>
                    <div class="field">
                        <label for="city2">City / area</label>
                        <x-city-filter-select id="city2" name="city" :selected="$filters['city'] ?? ''" :districts="$districts" placeholder="All cities" />
                    </div>
                    <div class="field">
                        <label class="field-label" style="opacity:0">Go</label>
                        <button class="btn-gold" type="submit" style="width:100%;min-height:46px">Filter</button>
                    </div>
                </div>
                <details class="mb-more" style="margin-top:12px">
                    <summary>Budget, BHK, built-up area</summary>
                    <div class="mb-more__body">
                        <div class="search-row mb-row-tight">
                            <div class="field">
                                <label for="bhk2">BHK</label>
                                <select class="input" id="bhk2" name="bhk">
                                    <option value="">Any</option>
                                    @foreach([1,2,3,4] as $b)
                                        <option value="{{ $b }}" {{ ($filters['bhk'] ?? '') === (string)$b ? 'selected' : '' }}>{{ $b }} BHK</option>
                                    @endforeach
                                    <option value="5" {{ ($filters['bhk'] ?? '') === '5' ? 'selected' : '' }}>5+ BHK</option>
                                </select>
                            </div>
                            <div class="field">
                                <label for="price_min2">Price min (LKR)</label>
                                <select class="input" id="price_min2" name="price_min">
                                    <option value="">Min</option>
                                    @foreach($budget_presets as $p)
                                        <option value="{{ $p }}" {{ (string)($filters['price_min'] ?? '') === (string)$p ? 'selected' : '' }}>{{ number_format($p / 1000000, ($p % 1000000 === 0 ? 0 : 1)) }} Mn</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label for="price_max2">Price max (LKR)</label>
                                <select class="input" id="price_max2" name="price_max">
                                    <option value="">Max</option>
                                    @foreach($budget_presets as $p)
                                        <option value="{{ $p }}" {{ (string)($filters['price_max'] ?? '') === (string)$p ? 'selected' : '' }}>{{ number_format($p / 1000000, ($p % 1000000 === 0 ? 0 : 1)) }} Mn</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="search-row mb-row-tight" style="margin-top:10px">
                            <div class="field">
                                <label for="area_min2">Sq.ft. min</label>
                                <select class="input" id="area_min2" name="area_min">
                                    <option value="">Min</option>
                                    @foreach($sqft_presets as $s)
                                        <option value="{{ $s }}" {{ (string)($filters['area_min'] ?? '') === (string)$s ? 'selected' : '' }}>{{ number_format($s) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label for="area_max2">Sq.ft. max</label>
                                <select class="input" id="area_max2" name="area_max">
                                    <option value="">Max</option>
                                    @foreach($sqft_presets as $s)
                                        <option value="{{ $s }}" {{ (string)($filters['area_max'] ?? '') === (string)$s ? 'selected' : '' }}>{{ number_format($s) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </details>
                @foreach(['kind', 'subtype', 'quick'] as $hidden)
                    @if(!empty($filters[$hidden]))
                        <input type="hidden" name="{{ $hidden }}" value="{{ $filters[$hidden] }}">
                    @endif
                @endforeach
            </form>

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
