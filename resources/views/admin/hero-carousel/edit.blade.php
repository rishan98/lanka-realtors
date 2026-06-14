@extends('layouts.admin')

@section('title', 'Homepage carousel — '.config('app.name'))

@section('admin_main')
<header class="agent-page-head">
    <div class="row-flex" style="justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
        <div>
            <h1>Homepage carousel</h1>
            <p>Upload between {{ \App\Models\HeroCarouselBanner::MIN_BANNERS }} and {{ \App\Models\HeroCarouselBanner::MAX_BANNERS }} banner images for the hero search carousel on the public homepage.</p>
        </div>
        <a class="pill" href="{{ route('admin.dashboard') }}">← Dashboard</a>
    </div>
</header>

<form method="post" action="{{ route('admin.hero-carousel.update') }}" enctype="multipart/form-data" class="admin-hero-carousel-form">
    @csrf
    @method('PUT')

    @include('admin.partials.banner-slots-form', [
        'banners' => $banners,
        'requireFirst' => true,
    ])

    <div class="row-flex" style="gap:12px;flex-wrap:wrap;margin-top:1rem">
        <button type="submit" class="btn-gold">Save banners</button>
        <a class="pill" href="{{ route('portal.home') }}" target="_blank" rel="noopener">Preview homepage</a>
    </div>
</form>
@endsection
