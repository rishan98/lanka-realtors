@props(['agent', 'compact' => false])

@php($sale = (int) ($agent->published_sale_count ?? 0))
@php($rent = (int) ($agent->published_rent_count ?? 0))
@php($href = route('agents.portfolio', $agent))

<article class="top-agent-card{{ $compact ? ' top-agent-card--compact' : '' }}">
    <a href="{{ $href }}" class="top-agent-card__link" aria-label="View {{ $agent->name }} portfolio">
        @if($compact)
            <img class="top-agent-card__photo" src="{{ $agent->avatarUrl() }}" alt="">
            <div class="top-agent-card__identity">
                <h3 class="top-agent-card__name">{{ $agent->name }}</h3>
            </div>
        @else
            <header class="top-agent-card__header">
                <img class="top-agent-card__photo" src="{{ $agent->avatarUrl() }}" width="56" height="56" alt="">
                <div class="top-agent-card__identity">
                    @if($agent->is_preferred)
                        <span class="top-agent-card__badge-label">LR Preferred</span>
                    @else
                        <span class="top-agent-card__badge-label top-agent-card__badge-label--muted">Verified agent</span>
                    @endif
                    <h3 class="top-agent-card__name">{{ $agent->name }}</h3>
                </div>
                @if($agent->is_preferred)
                    <span class="top-agent-card__medal" title="Preferred agent" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="9" r="6" fill="#c9a227" stroke="#a9861c" stroke-width="1"/>
                            <path d="M8 14l-2 8 6-3 6 3-2-8" fill="#c0392b" stroke="#922b21" stroke-width="0.5"/>
                        </svg>
                    </span>
                @endif
            </header>

            <div class="top-agent-card__body">
                <div class="top-agent-card__company">
                    @if($logo = $agent->companyLogoUrl())
                        <img class="top-agent-card__logo" src="{{ $logo }}" width="40" height="40" alt="">
                    @else
                        <span class="top-agent-card__logo-fallback" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($agent->companyDisplayName(), 0, 1)) }}</span>
                    @endif
                    <div class="top-agent-card__company-text">
                        <div class="top-agent-card__company-name">{{ $agent->companyDisplayName() }}</div>
                        <div class="top-agent-card__meta">
                            <span>Operating since @if($agent->operating_since_year)<strong>{{ $agent->operating_since_year }}</strong>@else<strong>â€”</strong>@endif</span>
                            <span class="top-agent-card__meta-div" aria-hidden="true">|</span>
                            <span>Buyers served @if($agent->buyers_served_estimate)<strong>{{ number_format($agent->buyers_served_estimate) }}+</strong>@else<strong>â€”</strong>@endif</span>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="top-agent-card__stats">
                <div class="top-agent-card__stat">
                    <span class="top-agent-card__stat-num">{{ $sale }}</span>
                    <span class="top-agent-card__stat-label">Properties for Sale</span>
                </div>
                <div class="top-agent-card__stat">
                    <span class="top-agent-card__stat-num">{{ $rent }}</span>
                    <span class="top-agent-card__stat-label">Properties for Rent</span>
                </div>
            </footer>
        @endif
    </a>
</article>

