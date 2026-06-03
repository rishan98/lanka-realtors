@extends('layouts.portal')

@section('title', $agent->name.' Portfolio — '.config('app.name'))

@section('content')
<section class="section section--tight">
    <div class="container">
        <nav class="listing-detail__breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('portal.home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('find-realtor') }}">Agents</a>
            <span aria-hidden="true">/</span>
            <span>{{ $agent->name }}</span>
        </nav>

        <header class="section-top-viewed__header" style="margin-top:16px;">
            <div>
                <h1 class="section-top-viewed__title" style="margin-bottom:10px;">{{ $agent->name }}</h1>
                <p class="muted mb-0">
                    {{ $agent->companyDisplayName() }}
                    @if($agent->operating_since_year)
                        · Operating since {{ $agent->operating_since_year }}
                    @endif
                    @if($agent->buyers_served_estimate)
                        · Buyers served {{ number_format($agent->buyers_served_estimate) }}+
                    @endif
                </p>
            </div>
        </header>

        <div class="listing-detail__section listing-detail__section--agent" style="padding-top:0;">
            <div class="listing-detail__agent-card">
                <x-agent-card :agent="$agent" />
            </div>
        </div>

        @if(!empty($agent->bio))
            <section class="listing-detail__section" style="margin-top:18px;">
                <h2 class="listing-detail__section-title">About {{ $agent->name }}</h2>
                <p class="muted" style="line-height:1.65;">{{ $agent->bio }}</p>
            </section>
        @endif

        <section class="listing-detail__section" style="margin-top:18px;">
            <header class="section-top-viewed__header" style="border-bottom:none; margin-bottom: 16px; padding-bottom: 0;">
                <h2 class="section-top-viewed__title" style="font-size:1.5rem;">Agent Ads</h2>
                <span class="section-top-viewed__link" style="pointer-events:none;">
                    {{ $listings->total() }} {{ \Illuminate\Support\Str::plural('listing', $listings->total()) }}
                </span>
            </header>

            @if($listings->isEmpty())
                <div class="block-gray">
                    <p class="muted mb-0">No published ads from this agent yet.</p>
                </div>
            @else
                <div class="card-grid card-grid--listings">
                    @foreach($listings as $listing)
                        <article class="property-card">
                            <a href="{{ route('listings.show', $listing) }}">
                                <img class="property-card__image" src="{{ $listing->imageUrl() }}" alt="{{ $listing->title }}">
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
        </section>
    </div>
</section>
@endsection
