@php
    $siteName = config('app.name');
    $routeName = Route::currentRouteName();
    $noIndex = \App\Support\Seo::shouldNoIndex($routeName);

    $title = ($seo['title'] ?? null) ?: trim($__env->yieldContent('title')) ?: $siteName;
    $description = ($seo['description'] ?? null) ?: trim($__env->yieldContent('meta_description')) ?: config('portal.seo.default_description');
    $canonical = ($seo['canonical'] ?? null) ?: trim($__env->yieldContent('canonical')) ?: url()->current();
    $image = ($seo['image'] ?? null) ?: trim($__env->yieldContent('og_image')) ?: (config('portal.logo') ? asset(config('portal.logo')) : config('portal.seo.default_image'));
    $robots = trim($__env->yieldContent('robots')) ?: ($noIndex ? 'noindex, nofollow' : 'index, follow');
    $ogType = ($seo['og_type'] ?? null) ?: trim($__env->yieldContent('og_type')) ?: 'website';
@endphp

<meta name="description" content="{{ $description }}">
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonical }}">

<meta property="og:type" content="{{ $ogType }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $canonical }}">
@if($image)
<meta property="og:image" content="{{ $image }}">
@endif

<meta name="twitter:card" content="{{ $image ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
@if($image)
<meta name="twitter:image" content="{{ $image }}">
@endif
