@props(['agent', 'static' => false])

@php($href = route('agents.portfolio', $agent))

@if($static)
<div class="mb-agent-card mb-agent-card--static">
@else
<a href="{{ $href }}" class="mb-agent-card">
@endif
    <div class="mb-agent-card__header">
        <img class="mb-agent-card__photo" src="{{ $agent->avatarUrl() }}" alt="{{ $agent->name }}">
        <div class="mb-agent-card__header-info">
            @if($agent->is_preferred)
                <div class="mb-agent-card__preferred-text">LR Preferred</div>
            @endif
            <h3 class="mb-agent-card__name">{{ $agent->name }}</h3>
        </div>
        @if($agent->hasRating())
            <div class="mb-agent-card__rating" aria-label="Rating {{ $agent->formattedRating() }} out of 5">
                <span class="mb-agent-card__rating-value">{{ $agent->formattedRating() }}</span>
                <span class="mb-agent-card__rating-star" aria-hidden="true">★</span>
            </div>
        @endif
    </div>
    <div class="mb-agent-card__body">
        @if($agent->companyLogoUrl())
            <img src="{{ $agent->companyLogoUrl() }}" class="mb-agent-card__company-logo" alt="{{ $agent->companyDisplayName() }} logo">
        @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode($agent->companyDisplayName()) }}&background=f5f5f5&color=424242" class="mb-agent-card__company-logo" alt="{{ $agent->companyDisplayName() }} logo">
        @endif
        <div class="mb-agent-card__company-info">
            <div class="mb-agent-card__company-name">{{ $agent->companyDisplayName() }}</div>
            <div class="mb-agent-card__company-stats">
                Operating Since {{ $agent->operating_since_year ?? date('Y') }}
                <div class="mb-agent-card__stat-divider"></div>
                Buyers Served {{ $agent->buyers_served_estimate ?? '100' }}+
            </div>
        </div>
    </div>
    <div class="mb-agent-card__footer">
        <div class="mb-agent-card__listing-stat">
            <div class="mb-agent-card__listing-count">{{ $agent->published_sale_count ?? 0 }}</div>
            <div class="mb-agent-card__listing-label">Properties<br>for Sale</div>
        </div>
        <div class="mb-agent-card__listing-stat">
            <div class="mb-agent-card__listing-count">{{ $agent->published_rent_count ?? 0 }}</div>
            <div class="mb-agent-card__listing-label">Properties<br>for Rent</div>
        </div>
    </div>
    @unless($static)
        <span class="mb-agent-card__cta">View {{ $agent->name }} portfolio</span>
    @endunless
@if($static)
</div>
@else
</a>
@endif
