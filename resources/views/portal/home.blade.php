@extends('layouts.portal')

@section('title', 'Buy, Rent & Sell Property in Sri Lanka — '.config('app.name'))
@section('meta_description', config('portal.seo.default_description'))
@section('canonical', route('portal.home'))

@section('content')
<section class="hero-search">
    <div class="hero-search__aurora" aria-hidden="true"></div>
    <div class="hero-search__mesh" aria-hidden="true"></div>

    <div class="container hero-search__layout">
        <div class="hero-search__intro">
            <!-- <p class="hero-search__eyebrow">
                <span class="hero-search__eyebrow-dot"></span>
                {{ $portal['tagline'] ?? 'Find your next address.' }}
            </p> -->
            <h1 class="hero-search__title">Search Properties with <span class="hero-search__title-accent">Realtor Expertise</span></h1>
        </div>

        <div class="hero-search__agents-wrap">
            @if($heroTopAgents->isEmpty())
                <p class="hero-search__agents-empty muted">Agent profiles appear here as listings go live. <a href="{{ route('register') }}">Join as an agent</a></p>
            @else
                <div class="hero-search__agents">
                    @foreach($heroTopAgents as $agent)
                        <x-top-agent-card :agent="$agent" :compact="true" />
                    @endforeach
                </div>
            @endif
        </div>

        <div class="hero-search__card-shell">
            @if(! empty($heroCarousel))
                <div class="hero-search__carousel-wrap">
                    <x-hero-carousel :slides="$heroCarousel" />
                </div>
            @endif
            <div class="glass glass--pad glass--hero-search mb-search-panel">
                <form action="{{ route('listings.index') }}" method="get" id="home-search">
                <div class="search-row mb-row-tight">
                    <div class="field mb-field-lg">
                        <label for="q">Enter city, locality, or project</label>
                        <input class="input input--hero" id="q" name="q" type="search" placeholder="e.g. Colombo 7, Negombo lagoon, Havelock City…" value="{{ request('q') }}" autocomplete="off">
                    </div>
                    <div class="field">
                        <label class="field-label" style="opacity:0">Search</label>
                        <button class="btn-gold btn-gold--hero-submit" type="submit">Search</button>
                    </div>
                </div>
            </form>
            </div>
        </div>

        <div class="mb-hot-links hero-search__popular">
            <span class="hero-search__popular-label">Popular</span>
            <a class="pill" href="{{ route('listings.index', ['quick' => 'apartments', 'city' => 'Colombo']) }}">Colombo apartments</a>
            <a class="pill" href="{{ route('listings.index', ['kind' => 'rental', 'subtype' => 'apartment', 'city' => 'Kandy']) }}">Kandy rent</a>
            <a class="pill" href="{{ route('listings.index', ['quick' => 'plot', 'city' => 'Galle']) }}">Galle land</a>
            <a class="pill" href="{{ route('listings.index', ['quick' => 'commercial', 'q' => 'office']) }}">Office space</a>
        </div>
    </div>
</section>

<section class="section-top-viewed" style="padding: 60px 0; background: #f8f9fa; border-top: 1px solid #eee; border-bottom: 1px solid #eee;">
    <div class="container">
        <header class="section-top-viewed__header" style="border-bottom:none; margin-bottom: 24px;">
            <h2 class="section-top-viewed__title">Top Agents</h2>
            <a href="{{ route('find-realtor') }}" class="section-top-viewed__link">See all Agents &rarr;</a>
        </header>

        @if($topAgents->isEmpty())
            <div class="block-gray mt-4">
                <p class="muted mb-0">No agents yet.</p>
            </div>
        @else
            <div class="mb-agent-grid">
                @foreach($topAgents as $agent)
                    <x-agent-card :agent="$agent" />
                @endforeach
            </div>
        @endif
    </div>
</section>

<section class="section-promo">
    <div class="section-promo__bg" aria-hidden="true"></div>
    <div class="container section-promo__container">
        <header class="section-promo__header">
            <p class="section-promo__eyebrow">Why Lanka Realtors</p>
            <h2 class="section-promo__title">We have got properties for everyone</h2>
            <p class="section-promo__lead">Curated paths to agents, new projects, and map-first discovery—built for how Sri Lankans actually search.</p>
        </header>

        <div class="section-promo__grid">
            @foreach($portal['promo_cards'] ?? [] as $card)
                <article class="promo-card promo-card--{{ $loop->iteration }}">
                    <div class="promo-card__glow" aria-hidden="true"></div>
                    <div class="promo-card__top">
                        <div class="promo-card__icon">
                            <x-promo-card-icon :index="$loop->index" />
                        </div>
                        <span class="promo-card__step">0{{ $loop->iteration }}</span>
                    </div>
                    <h3 class="promo-card__title">{{ $card['title'] }}</h3>
                    <p class="promo-card__text">{{ $card['text'] }}</p>
                    <a class="btn-gold btn-gold--promo" href="{{ route($card['route'], $card['params'] ?? []) }}">{{ $card['cta'] }}</a>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="section-top-viewed">
    <div class="container">
        <header class="section-top-viewed__header">
            <h2 class="section-top-viewed__title">Popular Owner Properties</h2>
            <a href="{{ route('listings.index') }}" class="section-top-viewed__link">See all Properties &rarr;</a>
        </header>

        @if($featured->isEmpty())
            <div class="block-gray mt-4">
                <p class="muted mb-0">No top viewed listings yet.</p>
            </div>
        @else
            <div class="modern-property-grid">
                @foreach($featured as $listing)
                    <x-modern-property-card :listing="$listing" />
                @endforeach
            </div>
        @endif
    </div>
</section>



<section class="section-advice">
    <div class="section-advice__bg" aria-hidden="true"></div>
    <div class="container section-advice__container">
        <header class="section-advice__header">
            <p class="section-advice__eyebrow">Guides &amp; resources</p>
            <h2 class="section-advice__title">Advice &amp; tools</h2>
            <p class="section-advice__lead">Research, locality discovery, and listing quality—your real estate companion, not a wall of links.</p>
        </header>

        <div class="section-advice__grid">
            @foreach($portal['advice_cards'] ?? [] as $card)
                @php($href = $card['href'] ?? (isset($card['route']) ? route($card['route'], $card['params'] ?? []) : '#'))
                <a href="{{ $href }}" class="advice-card advice-card--{{ $loop->iteration }}">
                    <span class="advice-card__shine" aria-hidden="true"></span>
                    <div class="advice-card__head">
                        <div class="advice-card__icon">
                            <x-advice-card-icon :index="$loop->index" />
                        </div>
                        <span class="advice-card__arrow" aria-hidden="true">→</span>
                    </div>
                    <h3 class="advice-card__title">{{ $card['title'] }}</h3>
                    <p class="advice-card__text">{{ $card['text'] }}</p>
                    <span class="advice-card__link-label">Explore</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="section-top-viewed" style="padding: 60px 0; background: #fff; border-top: 1px solid #eee;">
    <div class="container">
        <header class="section-top-viewed__header" style="border-bottom:none; margin-bottom: 24px;">
            <h2 class="section-top-viewed__title">Top Project Listings</h2>
            <a href="{{ route('listings.index', ['kind' => 'projects']) }}" class="section-top-viewed__link">See all projects &rarr;</a>
        </header>

        @if($projectListings->isEmpty())
            <div class="block-gray mt-4">
                <p class="muted mb-0">No project listings yet. <a href="{{ route('listings.index', ['kind' => 'projects']) }}">Browse projects</a> or post a new listing.</p>
            </div>
        @else
            <div class="mb-corridor-slider">
                @foreach($projectListings as $listing)
                    <x-invest-corridor-card :listing="$listing" />
                @endforeach
            </div>
        @endif
    </div>
</section>


@endsection

@push('scripts')
@include('partials.hero-carousel-script')
@endpush

