@extends('layouts.admin')

@section('title', ($kindMeta['nav_label'] ?? ucfirst($kind)).' banners — '.config('app.name'))

@section('admin_main')
<header class="agent-page-head">
    <div class="row-flex" style="justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
        <div>
            <h1>{{ $kindMeta['nav_label'] ?? ucfirst($kind) }} banners</h1>
            <p>Upload one banner image for the <strong>{{ strtolower($kindMeta['nav_label'] ?? $kind) }}</strong> listings page.</p>
        </div>
        <div class="row-flex" style="gap:8px;flex-wrap:wrap">
            <a class="pill" href="{{ route('admin.listing-banners.index') }}">← All categories</a>
            <a class="pill" href="{{ route('admin.dashboard') }}">Dashboard</a>
        </div>
    </div>
</header>

<form method="post" action="{{ route('admin.listing-banners.update', $kind) }}" enctype="multipart/form-data" class="admin-hero-carousel-form">
    @csrf
    @method('PUT')

    @include('admin.partials.banner-slots-form', [
        'banners' => $banners,
        'requireFirst' => false,
        'maxSlots' => \App\Models\HeroCarouselBanner::LISTING_MAX_BANNERS,
    ])

    <div class="row-flex" style="gap:12px;flex-wrap:wrap;margin-top:1rem">
        <button type="submit" class="btn-gold">Save banner</button>
        <a class="pill" href="{{ route($kindMeta['preview_route'], $kindMeta['preview_params'] ?? []) }}" target="_blank" rel="noopener">Preview page</a>
    </div>
</form>
@endsection
