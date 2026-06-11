@php
    $activeNavKind = null;

    if (request()->routeIs('listings.index') && request()->filled('kind')) {
        $activeNavKind = request('kind');
    } elseif (request()->routeIs('listings.show')) {
        $listing = request()->route('listing');
        if ($listing instanceof \App\Models\Listing) {
            $activeNavKind = $listing->listing_kind;
        }
    } elseif (request()->routeIs('invest')) {
        $activeNavKind = 'invest';
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
    <a href="{{ route('listings.index', ['kind' => 'invest']) }}" class="{{ trim($navActive('invest')) }}" @if($activeNavKind === 'invest') aria-current="page" @endif>Invest</a>
    <a href="{{ route('listings.index', ['kind' => 'wanted']) }}" class="{{ trim($navActive('wanted')) }}" @if($activeNavKind === 'wanted') aria-current="page" @endif>Wanted</a>
    <a href="{{ route('locate') }}" class="{{ trim($navRouteActive('locate')) }}" @if(request()->routeIs('locate')) aria-current="page" @endif>Locate me</a>
    <a href="{{ route('find-realtor') }}" class="{{ trim($navRouteActive('find-realtor')) }}" @if(request()->routeIs('find-realtor')) aria-current="page" @endif>Find realtor</a>
    <a href="{{ route('grab-me') }}" class="{{ trim($navRouteActive('grab-me')) }}" @if(request()->routeIs('grab-me')) aria-current="page" @endif>Grab me</a>
    <a href="{{ route('about') }}" class="{{ trim($navRouteActive('about')) }}" @if(request()->routeIs('about')) aria-current="page" @endif>About us</a>
    <a href="{{ route('contact') }}" class="{{ trim($navRouteActive('contact')) }}" @if(request()->routeIs('contact')) aria-current="page" @endif>Contact us</a>
</nav>
