@props(['slides' => []])

@php
    $slides = collect($slides)->filter(fn ($slide) => ! empty($slide['image']))->values();
@endphp

@if($slides->isNotEmpty())
<div class="hero-carousel" data-hero-carousel aria-roledescription="carousel" aria-label="Featured properties">
    <div class="hero-carousel__viewport">
        @foreach($slides as $index => $slide)
            @php
                $src = str_starts_with($slide['image'], 'http://') || str_starts_with($slide['image'], 'https://')
                    ? $slide['image']
                    : asset($slide['image']);
                $alt = $slide['alt'] ?? 'Featured property';
                $url = $slide['url'] ?? null;
            @endphp
            <div class="hero-carousel__slide{{ $index === 0 ? ' is-active' : '' }}" data-hero-carousel-slide aria-hidden="{{ $index === 0 ? 'false' : 'true' }}">
                @if($url)
                    <a class="hero-carousel__link" href="{{ $url }}">
                        <img class="hero-carousel__image" src="{{ $src }}" alt="{{ $alt }}" @if($index === 0) fetchpriority="high" @else loading="lazy" @endif>
                    </a>
                @else
                    <img class="hero-carousel__image" src="{{ $src }}" alt="{{ $alt }}" @if($index === 0) fetchpriority="high" @else loading="lazy" @endif>
                @endif
            </div>
        @endforeach
    </div>

    @if($slides->count() > 1)
        <div class="hero-carousel__dots" role="tablist" aria-label="Carousel slides">
            @foreach($slides as $index => $slide)
                <button
                    type="button"
                    class="hero-carousel__dot{{ $index === 0 ? ' is-active' : '' }}"
                    data-hero-carousel-dot
                    role="tab"
                    aria-label="Slide {{ $index + 1 }}"
                    aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                ></button>
            @endforeach
        </div>
    @endif
</div>
@endif
