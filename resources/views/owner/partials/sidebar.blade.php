@php
    $user = auth()->user();
    $active = fn (string ...$names) => request()->routeIs(...$names) ? ' is-active' : '';
@endphp

<aside class="agent-sidebar">
    <div class="agent-sidebar__profile">
        <img class="agent-sidebar__avatar" src="{{ $user->avatarUrl() }}" alt="">
        <div class="agent-sidebar__meta">
            <strong>{{ $user->name }}</strong>
            <span>Property owner</span>
        </div>
    </div>

    <nav class="agent-nav" aria-label="Owner menu">
        <a class="agent-nav__link{{ $active('owner.dashboard') }}" href="{{ route('owner.dashboard') }}">
            <span class="agent-nav__icon" aria-hidden="true">⌂</span>
            Dashboard
        </a>
        <a class="agent-nav__link{{ $active('owner.listings.index', 'owner.listings.edit', 'owner.listings.update') }}" href="{{ route('owner.listings.index') }}">
            <span class="agent-nav__icon" aria-hidden="true">▦</span>
            My listings
        </a>
        <a class="agent-nav__link{{ $active('owner.listings.create') }}" href="{{ route('owner.listings.create') }}">
            <span class="agent-nav__icon" aria-hidden="true">+</span>
            Post property
        </a>
        <a class="agent-nav__link{{ $active('owner.profile.*') }}" href="{{ route('owner.profile.edit') }}">
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
           onclick="event.preventDefault(); document.getElementById('owner-logout-form').submit();">
            Sign out
        </a>
        <form id="owner-logout-form" action="{{ route('logout') }}" method="POST" class="sr-only">
            @csrf
        </form>
    </div>
</aside>
