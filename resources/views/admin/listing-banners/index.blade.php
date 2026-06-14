@extends('layouts.admin')

@section('title', 'Listing banners — '.config('app.name'))

@section('admin_main')
<header class="agent-page-head">
    <div class="row-flex" style="justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
        <div>
            <h1>Listing banners</h1>
            <p>Upload one banner image for each listings category. Banners appear at the top of the listings page when that category is selected.</p>
        </div>
        <a class="pill" href="{{ route('admin.dashboard') }}">← Dashboard</a>
    </div>
</header>

<section class="admin-listing-banners-grid">
    @foreach($kinds as $kind => $meta)
        @php($hasBanner = $activeBannerKinds->contains($kind))
        <article class="admin-panel glass glass--pad admin-listing-banner-card">
            <h2 class="admin-listing-banner-card__title">{{ $meta['nav_label'] ?? ucfirst($kind) }}</h2>
            <p class="admin-listing-banner-card__meta">
                {{ $hasBanner ? 'Banner uploaded' : 'No banner uploaded yet' }}
            </p>
            <div class="row-flex" style="gap:8px;flex-wrap:wrap;margin-top:1rem">
                <a class="btn-gold" href="{{ route('admin.listing-banners.edit', $kind) }}">Manage banner</a>
                <a class="pill" href="{{ route('listings.index', ['kind' => $kind]) }}" target="_blank" rel="noopener">Preview</a>
            </div>
        </article>
    @endforeach
</section>
@endsection
