<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#eef1f5">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    @include('partials.seo')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400..700;1,9..40,400..700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/portal.css') }}" rel="stylesheet">
    @stack('head')
</head>
<body>
    <header class="site-header">
        <div class="site-header__top">
            <div class="container">
                <div class="site-header__bar">
                    <x-site-logo />

                    <div class="site-header__bar-end">
                        <div class="header-actions">
                            @auth
                                <a href="{{ auth()->user()->dashboardRoute() }}" class="btn-outline-header btn--header-sm">Agent dashboard</a>
                                @unless(auth()->user()->isAdmin())
                                    <a class="btn-gold btn-gold--header" href="{{ auth()->user()->postListingRoute() }}">Post your property ad</a>
                                @endunless
                                <form action="{{ route('logout') }}" method="POST" class="header-actions__logout">
                                    @csrf
                                    <button type="submit" class="btn-outline-header btn--header-sm">Log out</button>
                                </form>
                            @else
                                <a class="btn-gold btn-gold--header" href="{{ route('register', ['role' => 'owner']) }}">Post your property ad</a>
                                <a href="{{ route('login') }}" class="btn-outline-header btn--header-sm">Log in to Lanka Realtors</a>
                                <a href="{{ route('register') }}" class="btn-gold btn-gold--header">Create an account</a>
                            @endauth
                        </div>
                        <label class="nav-toggle-label" for="nav-toggle">Menu</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="site-header__nav-band">
            <div class="container site-header__stack">
                <input type="checkbox" id="nav-toggle" class="nav-toggle" aria-hidden="true">
                @include('components.primary-nav')
            </div>
        </div>
    </header>

    <main class="site-main">
        @yield('content')
    </main>

    <footer class="footer">
        @include('components.footer-mega')
        <div class="container footer__bottom">
            <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}. Built for agents and buyers in Sri Lanka.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
