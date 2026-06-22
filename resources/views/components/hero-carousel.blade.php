@props([
    'slides' => [],
    'modifier' => null,
    'label' => 'Featured properties',
])

@php
    $slides = collect($slides)->filter(fn ($slide) => ! empty($slide['image']))->values();
@endphp

@if($slides->isNotEmpty())
<div class="hero-carousel{{ $modifier ? ' hero-carousel--'.$modifier : '' }}" data-hero-carousel aria-roledescription="carousel" aria-label="{{ $label }}">
    <div class="hero-carousel__viewport">
        @foreach($slides as $index => $slide)
            @php
                $src = str_starts_with($slide['image'], 'http://') || str_starts_with($slide['image'], 'https://')
                    ? $slide['image']
                    : asset($slide['image']);
                $alt = $slide['alt'] ?? 'Featured property';
                $url = isset($slide['url']) ? trim((string) $slide['url']) : null;
                $crawlableUrl = is_string($url)
                    && $url !== ''
                    && $url !== '#'
                    && ! str_starts_with($url, '#')
                    && ! str_starts_with(strtolower($url), 'javascript:');
            @endphp
            <div class="hero-carousel__slide{{ $index === 0 ? ' is-active' : '' }}" data-hero-carousel-slide aria-hidden="{{ $index === 0 ? 'false' : 'true' }}">
                @if($crawlableUrl)
                    <a class="hero-carousel__link" href="{{ $url }}">
                        <span class="hero-carousel__link-text">{{ $alt }}</span>
                        <img class="hero-carousel__image" src="{{ $src }}" alt="" role="presentation" @if($index === 0) fetchpriority="high" @else loading="lazy" @endif>
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
