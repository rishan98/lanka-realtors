@php
    $user = auth()->user();
    $active = fn (string ...$names) => request()->routeIs(...$names) ? ' is-active' : '';
@endphp

<aside class="agent-sidebar">
    <div class="agent-sidebar__profile">
        <img class="agent-sidebar__avatar" src="{{ $user->avatarUrl() }}" alt="">
        <div class="agent-sidebar__meta">
            <strong>{{ $user->name }}</strong>
            <span>{{ $user->companyDisplayName() }}</span>
            @if($user->is_preferred)
                <span class="agent-badge">Preferred agent</span>
            @endif
        </div>
    </div>

    <nav class="agent-nav" aria-label="Agent menu">
        <a class="agent-nav__link{{ $active('agent.dashboard') }}" href="{{ route('agent.dashboard') }}">
            <span class="agent-nav__icon" aria-hidden="true">⌂</span>
            Dashboard
        </a>
        <a class="agent-nav__link{{ $active('agent.listings.index', 'agent.listings.edit', 'agent.listings.update') }}" href="{{ route('agent.listings.index') }}">
            <span class="agent-nav__icon" aria-hidden="true">▦</span>
            My listings
        </a>
        <a class="agent-nav__link{{ $active('agent.listings.create') }}" href="{{ route('agent.listings.create') }}">
            <span class="agent-nav__icon" aria-hidden="true">+</span>
            Post Your Ad
        </a>
        <a class="agent-nav__link{{ $active('agent.reviews.*') }}" href="{{ route('agent.reviews.index') }}">
            <span class="agent-nav__icon" aria-hidden="true">★</span>
            Reviews
            @if($user->pendingReviewCount() > 0)
                <span class="agent-nav__badge">{{ $user->pendingReviewCount() }}</span>
            @endif
        </a>
        <a class="agent-nav__link{{ $active('agent.profile.*') }}" href="{{ route('agent.profile.edit') }}">
            <span class="agent-nav__icon" aria-hidden="true">✎</span>
            Profile
        </a>
        <a class="agent-nav__link" href="{{ route('portal.home') }}">
            <span class="agent-nav__icon" aria-hidden="true">↗</span>
            View public site
        </a>
    </nav>

    <div class="agent-sidebar__foot">
        <a class="agent-nav__link agent-nav__link--muted" href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('agent-logout-form').submit();">
            Sign out
        </a>
        <form id="agent-logout-form" action="{{ route('logout') }}" method="POST" class="sr-only">
            @csrf
        </form>
    </div>
</aside>
