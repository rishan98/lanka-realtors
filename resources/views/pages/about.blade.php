@extends('layouts.portal')

@section('title', 'About us — '.config('app.name'))

@section('content')
@php($tagline = $portal['tagline'] ?? 'Properties for every Sri Lankan buyer & renter.')
<section class="section section--tight site-page">
    <div class="container">
        <h1 class="section-title">About Lanka Realtors</h1>
        <p class="section-lead">{{ $tagline }}</p>

        <div class="site-page__grid">
            <div class="glass glass--pad site-page__intro">
                <p>We built Lanka Realtors to make property discovery in Sri Lanka clearer—for buyers hunting the right home, owners who want a fair listing, and agents who earn trust through real inventory.</p>
                <p class="muted mb-0">Whether you are comparing apartments in Colombo, land in Galle, or rentals in Kandy, our categories, maps, and agent profiles help you move from search to conversation without the noise.</p>
            </div>

            <div class="site-page__cards">
                <article class="site-page__card">
                    <h2>For buyers & renters</h2>
                    <p class="muted mb-0">Browse sales, rentals, investment projects, and wanted posts. Filter by city, budget, and property type, then contact verified agents or owners directly.</p>
                </article>
                <article class="site-page__card">
                    <h2>For agents</h2>
                    <p class="muted mb-0">Publish rich listings with photos, pricing, and location. Build a public portfolio so buyers can see your active inventory before they reach out.</p>
                </article>
                <article class="site-page__card">
                    <h2>For owners</h2>
                    <p class="muted mb-0">List your property yourself or use Grab me to connect with a professional who can market it on your behalf.</p>
                </article>
            </div>

            <div class="glass glass--pad">
                <h2 class="site-page__subhead">What we stand for</h2>
                <ul class="site-page__list muted">
                    <li>Transparent listings with clear contact paths</li>
                    <li>Verified agent and owner registrations reviewed by our team</li>
                    <li>Local focus—cities and neighbourhoods across Sri Lanka</li>
                    <li>Tools that respect your time: search, map locate, and curated categories</li>
                </ul>
                <div class="row-flex mt-3">
                    <a class="btn-gold" href="{{ route('listings.index') }}">Browse properties</a>
                    <a class="pill" href="{{ route('contact') }}">Contact us</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
