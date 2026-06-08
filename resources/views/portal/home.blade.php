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
            <p class="hero-search__eyebrow">
                <span class="hero-search__eyebrow-dot"></span>
                {{ $portal['tagline'] ?? 'Find your next address.' }}
            </p>
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
            <div class="hero-search__card-glow" aria-hidden="true"></div>
            <div class="glass glass--pad glass--hero-search mb-search-panel">
                <form action="{{ route('listings.index') }}" method="get" id="home-search">
                <input type="hidden" name="quick" id="quick-field" value="">

                <div class="mb-tabs" role="tablist" aria-label="Listing type">
                    <button type="button" class="mb-tab is-active" data-quick="">All</button>
                    <button type="button" class="mb-tab" data-quick="buy">Buy</button>
                    <button type="button" class="mb-tab" data-quick="rent">Rent</button>
                    <button type="button" class="mb-tab" data-quick="pg">PG</button>
                    <button type="button" class="mb-tab" data-quick="plot">Plot / Land</button>
                    <button type="button" class="mb-tab" data-quick="commercial">Commercial</button>
                </div>

                <div class="field mb-field-lg">
                    <label for="q">Enter city, locality, or project</label>
                    <input class="input input--hero" id="q" name="q" type="search" placeholder="e.g. Colombo 7, Negombo lagoon, Havelock City…" value="{{ request('q') }}" autocomplete="off">
                </div>

                <div class="search-row mb-row-tight">
                    <div class="field">
                        <label for="city">City (optional)</label>
                        <x-city-filter-select id="city" name="city" :selected="request('city', '')" :districts="$districts" />
                    </div>
                    <div class="field">
                        <label class="field-label" style="opacity:0">Search</label>
                        <button class="btn-gold btn-gold--hero-submit" type="submit">Search</button>
                    </div>
                </div>

                <details class="mb-more">
                    <summary>More filters (BHK, budget, area)</summary>
                    <div class="mb-more__body">
                        <div class="field-label">BHK</div>
                        <div class="mb-chip-row">
                            @foreach([1,2,3,4] as $b)
                                <label class="mb-chip"><input type="radio" name="bhk" value="{{ $b }}" {{ request('bhk') == (string)$b ? 'checked' : '' }}> {{ $b }} BHK</label>
                            @endforeach
                            <label class="mb-chip"><input type="radio" name="bhk" value="5" {{ request('bhk') === '5' ? 'checked' : '' }}> 5+ BHK</label>
                        </div>
                        <div class="search-row mb-row-tight" style="margin-top:14px">
                            <div class="field">
                                <label for="price_min">Budget from (LKR)</label>
                                <select class="input" id="price_min" name="price_min">
                                    <option value="">Min</option>
                                    @foreach($budget_presets as $p)
                                        <option value="{{ $p }}" {{ (string)request('price_min') === (string)$p ? 'selected' : '' }}>{{ number_format($p / 1000000, ($p % 1000000 === 0 ? 0 : 1)) }} Mn</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label for="price_max">Budget to (LKR)</label>
                                <select class="input" id="price_max" name="price_max">
                                    <option value="">Max</option>
                                    @foreach($budget_presets as $p)
                                        <option value="{{ $p }}" {{ (string)request('price_max') === (string)$p ? 'selected' : '' }}>{{ number_format($p / 1000000, ($p % 1000000 === 0 ? 0 : 1)) }} Mn</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="search-row mb-row-tight" style="margin-top:10px">
                            <div class="field">
                                <label for="area_min">Built-up area from (sq.ft.)</label>
                                <select class="input" id="area_min" name="area_min">
                                    <option value="">Min sq.ft.</option>
                                    @foreach($sqft_presets as $s)
                                        <option value="{{ $s }}" {{ (string)request('area_min') === (string)$s ? 'selected' : '' }}>{{ number_format($s) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label for="area_max">Built-up area to (sq.ft.)</label>
                                <select class="input" id="area_max" name="area_max">
                                    <option value="">Max sq.ft.</option>
                                    @foreach($sqft_presets as $s)
                                        <option value="{{ $s }}" {{ (string)request('area_max') === (string)$s ? 'selected' : '' }}>{{ number_format($s) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </details>

                <p class="muted mb-recent" id="recent-wrap" style="margin-top:14px;font-size:0.88rem;display:none"><span class="field-label" style="display:inline;margin-right:8px">Recent</span><span id="recent-list"></span></p>
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
            <h2 class="section-top-viewed__title">Top Investment Listings</h2>
            <a href="{{ route('listings.index', ['kind' => 'invest']) }}" class="section-top-viewed__link">See all investments &rarr;</a>
        </header>

        @if($investListings->isEmpty())
            <div class="block-gray mt-4">
                <p class="muted mb-0">No investment listings yet. <a href="{{ route('listings.index', ['kind' => 'invest']) }}">Browse invest</a> or post a new listing.</p>
            </div>
        @else
            <div class="mb-corridor-slider">
                @foreach($investListings as $listing)
                    <x-invest-corridor-card :listing="$listing" />
                @endforeach
            </div>
        @endif
    </div>
</section>


@endsection

@push('scripts')
<script>
(function () {
    var form = document.getElementById('home-search');
    if (!form) return;
    var field = document.getElementById('quick-field');
    var tabs = form.querySelectorAll('.mb-tab');
    tabs.forEach(function (btn) {
        btn.addEventListener('click', function () {
            tabs.forEach(function (b) { b.classList.remove('is-active'); });
            btn.classList.add('is-active');
            field.value = btn.getAttribute('data-quick') || '';
        });
    });

    var KEY = 'lr_recent_searches';
    function readRecent() {
        try { return JSON.parse(localStorage.getItem(KEY) || '[]'); } catch (e) { return []; }
    }
    function writeRecent(arr) {
        localStorage.setItem(KEY, JSON.stringify(arr.slice(0, 8)));
    }
    function renderRecent() {
        var list = readRecent();
        var wrap = document.getElementById('recent-wrap');
        var el = document.getElementById('recent-list');
        if (!wrap || !el || !list.length) return;
        wrap.style.display = 'block';
        el.innerHTML = list.map(function (item) {
            var qs = new URLSearchParams();
            if (item.q) qs.set('q', item.q);
            if (item.city) qs.set('city', item.city);
            if (item.quick) qs.set('quick', item.quick);
            var url = '{{ route('listings.index') }}' + (qs.toString() ? ('?' + qs.toString()) : '');
            return '<a class="pill" style="margin:4px 4px 0 0;display:inline-block" href="' + url + '">' + (item.label || 'Search') + '</a>';
        }).join(' ');
    }
    renderRecent();

    form.addEventListener('submit', function () {
        var q = form.querySelector('[name="q"]').value.trim();
        var city = form.querySelector('[name="city"]').value.trim();
        var quick = field.value;
        var label = [quick && quick.toUpperCase(), city || q || 'Search'].filter(Boolean).join(' · ');
        var arr = readRecent();
        arr.unshift({ q: q, city: city, quick: quick, label: label });
        var seen = {};
        arr = arr.filter(function (x) {
            var k = (x.q||'') + '|' + (x.city||'') + '|' + (x.quick||'');
            if (seen[k]) return false;
            seen[k] = true;
            return true;
        });
        writeRecent(arr);
    });
})();
</script>
@endpush
