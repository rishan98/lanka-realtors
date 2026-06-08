@php
    $user = auth()->user();
    $active = fn (string ...$names) => request()->routeIs(...$names) ? ' is-active' : '';
    $pendingCount = \App\Models\User::pendingApproval()
        ->whereIn('role', [\App\Models\User::ROLE_AGENT, \App\Models\User::ROLE_OWNER])
        ->count();
@endphp

<aside class="agent-sidebar admin-sidebar">
    <div class="agent-sidebar__profile">
        <img class="agent-sidebar__avatar" src="{{ $user->avatarUrl() }}" alt="">
        <div class="agent-sidebar__meta">
            <strong>{{ $user->name }}</strong>
            <span>Administrator</span>
            <span class="agent-badge">Admin</span>
        </div>
    </div>

    <nav class="agent-nav" aria-label="Admin menu">
        <a class="agent-nav__link{{ $active('admin.dashboard') }}" href="{{ route('admin.dashboard') }}">
            <span class="agent-nav__icon" aria-hidden="true">⌂</span>
            Dashboard
        </a>
        <a class="agent-nav__link{{ $active('admin.users.*') }}" href="{{ route('admin.users.pending') }}">
            <span class="agent-nav__icon" aria-hidden="true">✓</span>
            Pending registrations
            @if($pendingCount > 0)
                <span class="admin-nav__badge">{{ $pendingCount }}</span>
            @endif
        </a>
        <a class="agent-nav__link{{ $active('admin.agents.*') }}" href="{{ route('admin.agents.index') }}">
            <span class="agent-nav__icon" aria-hidden="true">★</span>
            Agent ratings
        </a>
        <a class="agent-nav__link{{ $active('admin.cities.*') }}" href="{{ route('admin.cities.index') }}">
            <span class="agent-nav__icon" aria-hidden="true">⌖</span>
            Cities
        </a>
        <a class="agent-nav__link{{ $active('admin.contact-inquiries.*') }}" href="{{ route('admin.contact-inquiries.index') }}">
            <span class="agent-nav__icon" aria-hidden="true">✉</span>
            Contact inquiries
        </a>
        <a class="agent-nav__link" href="{{ route('listings.index') }}">
            <span class="agent-nav__icon" aria-hidden="true">▦</span>
            All listings
        </a>
        <a class="agent-nav__link" href="{{ route('portal.home') }}">
            <span class="agent-nav__icon" aria-hidden="true">↗</span>
            View public site
        </a>
    </nav>

    <div class="agent-sidebar__foot">
        <a class="agent-nav__link agent-nav__link--muted" href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
            Sign out
        </a>
        <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" class="sr-only">
            @csrf
        </form>
    </div>
</aside>
