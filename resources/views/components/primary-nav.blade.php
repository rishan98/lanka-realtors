@php
    $activeNavKind = null;

    if (request()->routeIs('listings.index') && request()->filled('kind')) {
        $activeNavKind = request('kind');
    } elseif (request()->routeIs('lands.index')) {
        $activeNavKind = 'lands';
    } elseif (request()->routeIs('listings.show')) {
        $listing = request()->route('listing');
        if ($listing instanceof \App\Models\Listing) {
            $activeNavKind = $listing->property_subtype === 'land' ? 'lands' : $listing->listing_kind;
        }
    } elseif (request()->routeIs('projects')) {
        $activeNavKind = 'projects';
    }

    $navActive = function (string $key) use ($activeNavKind): string {
        return $activeNavKind === $key ? ' is-active' : '';
    };

    $navRouteActive = fn (string ...$routes): string => request()->routeIs(...$routes) ? ' is-active' : '';
@endphp

<nav class="nav nav--primary" aria-label="Property categories">
    <a href="{{ route('portal.home') }}" class="{{ trim($navRouteActive('portal.home')) }}" @if(request()->routeIs('portal.home')) aria-current="page" @endif>Home</a>
    <a href="{{ route('listings.index', ['kind' => 'sale']) }}" class="{{ trim($navActive('sale')) }}" @if($activeNavKind === 'sale') aria-current="page" @endif>Sales</a>
    <a href="{{ route('listings.index', ['kind' => 'rental']) }}" class="{{ trim($navActive('rental')) }}" @if($activeNavKind === 'rental') aria-current="page" @endif>Rental</a>
    <a href="{{ route('lands.index') }}" class="{{ trim($navActive('lands')) }}" @if($activeNavKind === 'lands') aria-current="page" @endif>Lands</a>
    <a href="{{ route('listings.index', ['kind' => 'projects']) }}" class="{{ trim($navActive('projects')) }}" @if($activeNavKind === 'projects') aria-current="page" @endif>Projects</a>
    <a href="{{ route('listings.index', ['kind' => 'wanted']) }}" class="{{ trim($navActive('wanted')) }}" @if($activeNavKind === 'wanted') aria-current="page" @endif>Wanted</a>
    <a href="{{ route('locate') }}" class="{{ trim($navRouteActive('locate')) }}" @if(request()->routeIs('locate')) aria-current="page" @endif>Locate me</a>
    <a href="{{ route('find-realtor') }}" class="{{ trim($navRouteActive('find-realtor')) }}" @if(request()->routeIs('find-realtor')) aria-current="page" @endif>Find realtor</a>
    <a href="{{ route('owners') }}" class="{{ trim($navRouteActive('owners')) }}" @if(request()->routeIs('owners')) aria-current="page" @endif>Owners</a>
    <a href="{{ route('about') }}" class="{{ trim($navRouteActive('about')) }}" @if(request()->routeIs('about')) aria-current="page" @endif>About us</a>
    <a href="{{ route('contact') }}" class="{{ trim($navRouteActive('contact')) }}" @if(request()->routeIs('contact')) aria-current="page" @endif>Contact us</a>
</nav>
