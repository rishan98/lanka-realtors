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

        <header class="agent-portfolio__hero">
            <div class="agent-portfolio__hero-main">
                <p class="agent-portfolio__eyebrow">
                    <span class="agent-portfolio__eyebrow-dot" aria-hidden="true"></span>
                    Agent portfolio
                </p>
                <h1 class="agent-portfolio__title">{{ $agent->name }}</h1>
                <p class="agent-portfolio__company">{{ $agent->companyDisplayName() }}</p>

                <ul class="agent-portfolio__meta-list">
                    @if($agent->is_preferred)
                        <li><span class="agent-portfolio__badge">LR Preferred</span></li>
                    @endif
                    @if($agent->operating_since_year)
                        <li>Operating since <strong>{{ $agent->operating_since_year }}</strong></li>
                    @endif
                    @if($agent->buyers_served_estimate)
                        <li>Buyers served <strong>{{ number_format($agent->buyers_served_estimate) }}+</strong></li>
                    @endif
                    <li><strong>{{ $totalPublished }}</strong> published {{ \Illuminate\Support\Str::plural('listing', $totalPublished) }}</li>
                </ul>
            </div>

            <figure class="agent-portfolio__photo">
                <img src="{{ $agent->avatarUrl() }}" alt="{{ $agent->name }}" width="380" height="480" loading="eager" decoding="async">
            </figure>
        </header>

        @if($agent->phone || $agent->email)
            <section class="agent-portfolio__contact glass glass--pad">
                <h2 class="agent-portfolio__contact-title">Contact {{ $agent->name }}</h2>
                <div class="agent-portfolio__contact-grid">
                    @if($agent->phone)
                        <a href="tel:{{ preg_replace('/\s+/', '', $agent->phone) }}" class="agent-portfolio__contact-item">
                            <span class="agent-portfolio__contact-label">Phone</span>
                            <span class="agent-portfolio__contact-value">{{ $agent->phone }}</span>
                        </a>
                    @endif
                    @if($agent->email)
                        <a href="mailto:{{ $agent->email }}" class="agent-portfolio__contact-item">
                            <span class="agent-portfolio__contact-label">Email</span>
                            <span class="agent-portfolio__contact-value">{{ $agent->email }}</span>
                        </a>
                    @endif
                </div>
            </section>
        @endif

        @if(!empty($agent->bio))
            <section class="agent-portfolio__bio glass glass--pad">
                <h2 class="agent-portfolio__bio-title">About {{ $agent->name }}</h2>
                <p class="agent-portfolio__bio-text">{{ $agent->bio }}</p>
            </section>
        @endif

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
